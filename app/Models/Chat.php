<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one_id', 'user_one_type', 'user_two_id', 'user_two_type',
        'last_message', 'last_message_at', 'unread_count_admin', 'unread_count_client'
    ];
    
    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
