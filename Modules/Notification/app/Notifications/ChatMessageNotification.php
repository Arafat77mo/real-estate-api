<?php

namespace Modules\Notification\App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ChatMessageNotification extends Notification
{
    use Queueable;

    protected $chatMessage;

    public function __construct($chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }
    public function broadcastOn()
    {
        return new Channel('chat');
    }

    public function broadcastAs()
    {
        return 'chat';
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id'             => $this->id,
            'user_id' => $this->chatMessage->thread->user_id,
            'agent_id' => $this->chatMessage->thread->agent_id,
            'chat_thread_id' => $this->chatMessage->chat_thread_id,
            'message'        => $this->chatMessage->message,
            'sender'         => [
                'id'       => $this->chatMessage->sender->id,
                'name'     => $this->chatMessage->sender->name,
            ],
            'is_me'          => $this->chatMessage->sender_id === auth()->id(), // تحديد هل المستخدم هو المرسل
            'created_at'     => $this->chatMessage->created_at->toDateTimeString(),
        ]);
    }


}
