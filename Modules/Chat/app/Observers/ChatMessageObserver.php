<?php

namespace Modules\Chat\app\Observers;


use Illuminate\Support\Facades\Auth;
use Modules\Chat\App\Events\ChatThreadUpdated;
use Modules\Chat\App\Models\ChatMessage;
use Modules\Chat\App\Models\ChatThread;
use Modules\Chat\App\Transformers\MessageResource;
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

            $chatMessage->thread->user->notify(new ChatMessageNotification($chatMessage ));

        $userId = Auth::id();

        // Fetch threads with required relationships
        $threads = ChatThread::with([
            'user:id,name',
            'agent:id,name',
            'messages' => function ($query) {
                $query->select('id', 'chat_thread_id', 'message', 'sender_id', 'is_read', 'created_at')
                    ->latest(); // Load only the latest message
            }
        ])
            ->where('user_id', $userId)
            ->orWhere('agent_id', $userId)
            ->withCount(['messages as unread_count' => function ($query) use ($userId) {
                $query->where('is_read', 0)->where('sender_id', '!=', $userId);
            }])
            ->orderByDesc(
                ChatThread::select('created_at')
                    ->from('chat_messages')
                    ->whereColumn('chat_messages.chat_thread_id', 'chat_threads.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
            )
            ->get();

        // Format the data before returning
        $chatThread= $threads->map(function ($thread) use ($userId) {
            $latestMessage = $thread->messages->first();

            // Determine the participant in the conversation
            $participant = ($thread->user_id === $userId) ? $thread->agent : $thread->user;

            return [
                'id'           => $thread->id,
                'participant'  => [
                    'id'   => $participant->id,
                    'name' => $participant->name,
                ],
                'last_message' => $latestMessage?->message,
                'unread_count' => $thread->unread_count,
                'updated_at'   => $thread->updated_at->format('Y-m-d H:i:s'),
                'is_me'        => $latestMessage?->sender_id === $userId,
            ];
        });
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
