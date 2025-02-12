<?php

namespace Modules\Chat\app\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Chat\App\Models\ChatMessage;
use Modules\Chat\App\Models\ChatThread;
use Modules\Notification\App\Helpers\ResponseData;
use Modules\Notification\App\Notifications\ChatMessageNotification;

class ChatService
{
    public function __construct(protected ChatMessage $chatMessage , protected ChatThread $chatThread)
    {

    }
    public function sendMessage($chatMessageRequest)
    {
        $user = Auth::user();

        // Check for an existing thread in both directions
        $thread = $this->chatThread::where(function ($query) use ($user, $chatMessageRequest) {
            $query->where('user_id', $user->id)
                ->where('agent_id', $chatMessageRequest['agent_id']);
        })
            ->orWhere(function ($query) use ($user, $chatMessageRequest) {
                $query->where('user_id', $chatMessageRequest['agent_id'])
                    ->where('agent_id', $user->id);
            })
            ->first();

        // If no thread exists, create a new one
        if (!$thread) {
            $thread = $this->chatThread::create([
                'user_id' => $user->id,
                'agent_id' => $chatMessageRequest['agent_id'],
            ]);
        }

        // Create the message
        $chatMessage = $this->chatMessage::create([
            'chat_thread_id' => $thread->id,
            'sender_id' => $user->id,
            'message' => $chatMessageRequest['message'],
        ]);

        return $chatMessage;
    }
    /**
     * جلب جميع الرسائل لمحادثة معينة
     */
    public function getMessages($otherUserId)
    {
        $currentUserId = auth()->id();
        $userId = $otherUserId['user_id'];

        // Find the chat thread between the two users
        $thread = $this->chatThread::where(function ($query) use ($currentUserId, $userId) {
            $query->where('user_id', $currentUserId)
                ->where('agent_id', $userId);
        })
            ->orWhere(function ($query) use ($currentUserId, $userId) {
                $query->where('user_id', $userId)
                    ->where('agent_id', $currentUserId);
            })
            ->with(['messages.sender']) // Eager load messages with sender
            ->first();

        // If no thread is found, return null or an empty collection
        if (!$thread) {
            return collect(); // Return an empty collection instead of null
        }

        // Mark unread messages as read (if the current user is the receiver)
        $thread->messages()
            ->where('is_read', 0)
            ->where('sender_id', '!=', $currentUserId)
            ->update(['is_read' => true]);

        // Fetch messages in chronological order with sender relationship
        $messages = $thread->messages()
            ->with(['sender']) // Eager load sender
            ->orderBy('created_at', 'asc')
            ->get();

        return $messages;
    }


    /**
     * جلب كل المحادثات الخاصة بالمستخدم
     */
    public function getUserThreads()
    {
        $userId = Auth::id();

        // Fetch threads with required relationships
        $threads = $this->chatThread::with([
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
            ->orderBy('updated_at', 'desc')
            ->get();

        // Format the data before returning
        return $threads->map(function ($thread) use ($userId) {
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
    }



}
