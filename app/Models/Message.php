<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\EncryptionService;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'admin_id',
        'sender_type',
        'message_type',
        'encrypted_subject',
        'encrypted_body',
        'encryption_key_id',
        'is_read',
        'is_replied',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function getDecryptedSubjectAttribute()
    {
        return EncryptionService::decrypt($this->encrypted_subject);
    }

    public function getDecryptedBodyAttribute()
    {
        return EncryptionService::decrypt($this->encrypted_body);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}