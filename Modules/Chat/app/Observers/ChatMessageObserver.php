<?php

namespace Modules\Chat\app\Observers;


use Modules\Chat\App\Models\ChatMessage;
use Modules\Notification\App\Notifications\ChatMessageNotification;

class ChatMessageObserver
{
    /**
     * Handle the ChatMessage "created" event.
     */
    public function created(ChatMessage $chatMessage): void
    {
        // تأكد من تحميل بيانات المحادثة والمستخدمين المرتبطين بها إذا لم تكن محملة مسبقًا
        $chatMessage->loadMissing('thread.user', 'thread.agent');
        $user =auth()->user();
        // إرسال إشعار للطرفين (المستخدم والوكيل) عند إنشاء الرسالة

            $chatMessage->thread->user->notify(new ChatMessageNotification($chatMessage));

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
