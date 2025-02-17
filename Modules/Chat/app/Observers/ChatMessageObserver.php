<?php

namespace Modules\Chat\app\Observers;


use Illuminate\Support\Facades\Auth;
use Modules\Chat\App\Events\ChatThreadUpdated;
use Modules\Chat\App\Models\ChatMessage;
use Modules\Chat\App\Models\ChatThread;
use Modules\Chat\app\Services\ChatService;
use Modules\Chat\App\Transformers\MessageResource;
use Modules\Notification\App\Notifications\ChatMessageNotification;

class ChatMessageObserver
{ use \Modules\Chat\app\Traits\ChatThread;
    /**
     * Handle the ChatMessage "created" event.
     */
    public function created(ChatMessage $chatMessage): void
    {
        $chatMessage->loadMissing('thread.user', 'thread.agent');
        $user =auth()->user();
            $chatMessage->thread->user->notify(new ChatMessageNotification($chatMessage ));

        $chatThread= $this->thread();

        event(new ChatThreadUpdated($chatThread));


    }



    /**
     * Handle the ChatMessage "updated" event.
     */
    public function updated(ChatMessage $chatMessage): void
    {
        //
    }

    /**
     * Handle the ChatMessage "deleted" event.
     */
    public function deleted(ChatMessage $chatMessage): void
    {
        //
    }

    /**
     * Handle the ChatMessage "restored" event.
     */
    public function restored(ChatMessage $chatMessage): void
    {
        //
    }

    /**
     * Handle the ChatMessage "force deleted" event.
     */
    public function forceDeleted(ChatMessage $chatMessage): void
    {
        //
    }
}
