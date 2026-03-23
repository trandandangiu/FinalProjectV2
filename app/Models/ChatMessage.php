<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'chat_messages';

    // Các cột cho phép gán dữ liệu hàng loạt
    protected $fillable = [
        'user_id',
        'guest_token',
        'sender',
        'message',
    ];

    /**
     * Quan hệ: mỗi tin nhắn thuộc về một user (nếu có).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
