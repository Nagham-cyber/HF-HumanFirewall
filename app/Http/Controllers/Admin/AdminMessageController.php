<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\EncryptionService;
use App\Models\AdminAuditLog;

class AdminMessageController extends Controller
{
    public function index()
    {
        $usersWithMessages = DB::table('messages')
            ->join('users', 'messages.sender_id', '=', 'users.id')
            ->where('messages.sender_type', 'user')
            ->where('messages.message_type', 'direct')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(messages.id) as message_count'),
                DB::raw('MAX(messages.created_at) as last_message')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('last_message', 'desc')
            ->get();

        $announcements = DB::table('messages')
            ->where('message_type', 'announcement')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($announcements as $ann) {
            $decrypted = EncryptionService::decryptMessage($ann->encrypted_subject, $ann->encrypted_body);
            $ann->decrypted_subject = $decrypted['subject'];
            $ann->decrypted_body = $decrypted['body'];
        }

        $allUsers = DB::table('users')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'xp_points']);

        $stats = [
            'total_messages' => DB::table('messages')->count(),
            'unread' => DB::table('messages')->where('is_read', false)->where('sender_type', 'user')->count(),
            'announcements' => DB::table('messages')->where('message_type', 'announcement')->count(),
            'active_conversations' => $usersWithMessages->count(),
        ];

        return view('admin.messages.index', compact('usersWithMessages', 'announcements', 'allUsers', 'stats'));
    }

    public function conversation($userId)
    {
        $user = DB::table('users')->find($userId);

        if (!$user) {
            return redirect()->route('admin.messages.index')->with('error', 'User not found.');
        }

        $messages = DB::table('messages')
            ->where(function ($q) use ($userId) {
                $q->where(function ($inner) use ($userId) {
                    $inner->where('sender_id', $userId)
                        ->where('sender_type', 'user')
                        ->where('message_type', 'direct');
                })->orWhere(function ($inner) use ($userId) {
                    $inner->where('receiver_id', $userId)
                        ->where('sender_type', 'admin')
                        ->where('message_type', 'direct');
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($messages as $msg) {
            $decrypted = EncryptionService::decryptMessage($msg->encrypted_subject, $msg->encrypted_body);
            $msg->decrypted_subject = $decrypted['subject'];
            $msg->decrypted_body = $decrypted['body'];
        }

        DB::table('messages')
            ->where('sender_id', $userId)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('admin.messages.conversation', compact('user', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
        ]);

        $admin = Auth::guard('admin')->user();
        $encrypted = EncryptionService::encryptMessage($request->subject, $request->body);

        DB::table('messages')->insert([
            'sender_id' => null,
            'receiver_id' => $request->receiver_id,
            'admin_id' => $admin->id,
            'sender_type' => 'admin',
            'message_type' => 'direct',
            'encrypted_subject' => $encrypted['subject'],
            'encrypted_body' => $encrypted['body'],
            'encryption_key_id' => 'v1',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'message_sent',
            'description' => "Message sent to user ID: {$request->receiver_id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Message sent successfully!');
    }

    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
        ]);

        $admin = Auth::guard('admin')->user();
        $encrypted = EncryptionService::encryptMessage($request->title, $request->body);

        $users = DB::table('users')->pluck('id');

        foreach ($users as $userId) {
            DB::table('messages')->insert([
                'sender_id' => null,
                'receiver_id' => $userId,
                'admin_id' => $admin->id,
                'sender_type' => 'admin',
                'message_type' => 'announcement',
                'encrypted_subject' => $encrypted['subject'],
                'encrypted_body' => $encrypted['body'],
                'encryption_key_id' => 'v1',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'announcement_sent',
            'description' => "Announcement sent to {$users->count()} users",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', "Announcement sent to {$users->count()} users!");
    }
    /**
     * حذف محادثة كاملة مع مستخدم
     */
    public function deleteConversation(Request $request, $userId)
    {
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();

        $user = DB::table('users')->find($userId);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // احذف كل الرسائل المباشرة بين الأدمن والمستخدم
        $deleted = DB::table('messages')
            ->where('message_type', 'direct')
            ->where(function ($q) use ($userId) {
                $q->where(function ($inner) use ($userId) {
                    $inner->where('sender_id', $userId)
                        ->where('sender_type', 'user');
                })->orWhere(function ($inner) use ($userId) {
                    $inner->where('receiver_id', $userId)
                        ->where('sender_type', 'admin');
                });
            })
            ->delete();

        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'conversation_deleted',
            'description' => "Deleted conversation with {$user->name} ({$deleted} messages)",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.messages.index')->with('success', "Conversation with {$user->name} deleted ({$deleted} messages).");
    }
}