<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\EncryptionService;

class UserMessageController extends Controller
{
    /**
     * صفحة الرسائل
     */
    public function index()
    {
        $user = Auth::user();

        // الإشعارات الجماعية
        $announcements = DB::table('messages')
            ->where('receiver_id', $user->id)
            ->where('message_type', 'announcement')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // فك تشفيرها
        foreach ($announcements as $ann) {
            $decrypted = EncryptionService::decryptMessage($ann->encrypted_subject, $ann->encrypted_body);
            $ann->decrypted_subject = $decrypted['subject'];
            $ann->decrypted_body = $decrypted['body'];
        }

        // المحادثة الفردية مع الأدمن
        $directMessages = DB::table('messages')
            ->where(function ($q) use ($user) {
                $q->where(function ($inner) use ($user) {
                    $inner->where('sender_id', $user->id)
                        ->where('sender_type', 'user');
                })->orWhere(function ($inner) use ($user) {
                    $inner->where('receiver_id', $user->id)
                        ->where('sender_type', 'admin');
                });
            })
            ->where('message_type', 'direct')
            ->orderBy('created_at', 'asc')
            ->get();

        // فك تشفيرها
        foreach ($directMessages as $msg) {
            $decrypted = EncryptionService::decryptMessage($msg->encrypted_subject, $msg->encrypted_body);
            $msg->decrypted_subject = $decrypted['subject'];
            $msg->decrypted_body = $decrypted['body'];
        }

        // تعليم الرسائل الفردية كمقروءة
        DB::table('messages')
            ->where('receiver_id', $user->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // إحصائيات
        $stats = [
            'unread_announcements' => DB::table('messages')
                ->where('receiver_id', $user->id)
                ->where('message_type', 'announcement')
                ->where('is_read', false)
                ->count(),
            'total_direct' => $directMessages->count(),
            'total_announcements' => $announcements->count(),
        ];

        return view('user-messages.index', compact('announcements', 'directMessages', 'stats'));
    }

    /**
     * إرسال رسالة للأدمن
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
        ]);

        $user = Auth::user();
        $encrypted = EncryptionService::encryptMessage($request->subject, $request->body);

        DB::table('messages')->insert([
            'sender_id' => $user->id,
            'receiver_id' => null,
            'admin_id' => null,
            'sender_type' => 'user',
            'message_type' => 'direct',
            'encrypted_subject' => $encrypted['subject'],
            'encrypted_body' => $encrypted['body'],
            'encryption_key_id' => 'v1',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Message sent to admin successfully!');
    }

    /**
     * تعليم إشعار كمقروء
     */
    public function markAnnouncementRead($id)
    {
        $user = Auth::user();

        DB::table('messages')
            ->where('id', $id)
            ->where('receiver_id', $user->id)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}