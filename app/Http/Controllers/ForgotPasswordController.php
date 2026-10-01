<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $request->email],
            [
                'code' => Hash::make($code),
                'expires_at' => now()->addMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // إرسال البريد
        try {
            Mail::send('emails.reset-code', ['code' => $code, 'user' => $user], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Password Reset Code - HF');
            });
        } catch (\Exception $e) {
            // في حالة فشل البريد - نعرض الكود مباشرة للتطوير
            return response()->json([
                'success' => true,
                'message' => 'Code sent! (Dev mode: ' . $code . ')',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your email!',
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->code, $record->code)) {
            return response()->json(['success' => false, 'message' => 'Invalid code.']);
        }

        if (now()->gt($record->expires_at)) {
            return response()->json(['success' => false, 'message' => 'Code expired.']);
        }

        return response()->json(['success' => true, 'message' => 'Code verified!']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->code, $record->code)) {
            return response()->json(['success' => false, 'message' => 'Invalid code.']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        return response()->json(['success' => true, 'message' => 'Password reset! Login now.']);
    }
}