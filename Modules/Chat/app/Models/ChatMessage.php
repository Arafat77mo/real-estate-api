<?php

namespace Modules\Chat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Chat\Database\Factories\ChatMessageFactory;

class ChatMessage extends Model
{
    protected $fillable = [
        'chat_thread_id',
        'sender_id',
        'message',
        'is_read'
    ];

    public function thread()
    {
        return $this->belongsTo(ChatThread::class, 'chat_thread_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
