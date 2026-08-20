<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id', 'sender_id', 'sender_type', 'message',
        'is_read', 'file_path', 'file_type', 'file_name', 'file_size'
    ];
}
