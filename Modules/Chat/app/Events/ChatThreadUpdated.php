<?php

namespace Modules\Chat\App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Chat\App\Transformers\ChatResource;

// أو المسار الصحيح للمورد الخاص بالثريد

class ChatThreadUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $thread;

    /**
     * Create a new event instance.
     *
     * @param mixed $thread
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|Channel[]
     */
    public function broadcastOn()
    {
        return new Channel('chatThreads');
    }

    /**
     * تعيين اسم الحدث الذي سيتم بثه.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'thread.updated';
    }
}
