<?php

namespace Modules\Chat\app\Traits;


use Illuminate\Support\Facades\Auth;

trait ChatThread
{
    protected function thread()
    {
        $userId = Auth::id();

        $threads = \Modules\Chat\App\Models\ChatThread::with([
            'user:id,name',
            'agent:id,name',
            'latestMessage' => function ($query) {
                $query->select('id', 'chat_thread_id', 'message', 'sender_id', 'is_read', 'created_at')
                    ->latest();
            }
        ])
            ->where('user_id', $userId)
            ->orWhere('agent_id', $userId)
            ->withCount(['messages as unread_count' => function ($query) use ($userId) {
                $query->where('is_read', 0)->where('sender_id', '!=', $userId);
            }])
            ->orderByDesc(
                \Modules\Chat\App\Models\ChatThread::select('created_at')
                    ->from('chat_messages')
                    ->whereColumn('chat_messages.chat_thread_id', 'chat_threads.id')
                    ->latest()
                    ->limit(1)
            )
            ->get();

        return $threads->map(function ($thread) use ($userId) {
            $latestMessage = $thread->latestMessage;

            $participant = ($thread->user_id === $userId) ? $thread->agent : $thread->user;

            return [
                'id' => $thread->id,
                'participant' => [
                    'id' => $participant->id,
                    'name' => $participant->name,
                ],
                'last_message' => $latestMessage?->message,
                'unread_count' => $thread->unread_count,
                'updated_at' => $thread->updated_at->format('Y-m-d H:i:s'),
                'is_me' => $latestMessage?->sender_id === $userId,
            ];
        });
    }


}
