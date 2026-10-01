<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptionService
{
    /**
     * تشفير النص
     */
    public static function encrypt($plaintext)
    {
        if (empty($plaintext)) {
            return null;
        }

        return Crypt::encryptString($plaintext);
    }

    /**
     * فك تشفير النص
     */
    public static function decrypt($ciphertext)
    {
        if (empty($ciphertext)) {
            return null;
        }

        try {
            return Crypt::decryptString($ciphertext);
        } catch (\Exception $e) {
            return '[Encrypted - Cannot Decrypt]';
        }
    }

    /**
     * تشفير رسالة كاملة
     */
    public static function encryptMessage($subject, $body)
    {
        return [
            'subject' => self::encrypt($subject),
            'body' => self::encrypt($body),
        ];
    }

    /**
     * فك تشفير رسالة كاملة
     */
    public static function decryptMessage($encryptedSubject, $encryptedBody)
    {
        return [
            'subject' => self::decrypt($encryptedSubject),
            'body' => self::decrypt($encryptedBody),
        ];
    }
}