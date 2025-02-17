<?php

namespace Modules\Chat\app\Services;

use e;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Chat\App\Models\ChatMessage;
use Modules\Chat\App\Models\ChatThread;

class ChatService
{ use \Modules\Chat\app\Traits\ChatThread;
    public function __construct(protected ChatMessage $chatMessage, protected ChatThread $chatThread)
    {

    }

    public function sendMessage($chatMessageRequest)
    {
        return DB::transaction(function () use ($chatMessageRequest) {
            $user = Auth::user();
            $agentId = $chatMessageRequest['agent_id'];

            $thread = $this->chatThread::where(function ($query) use ($user, $agentId) {
                $query->where('user_id', $user->id)
                    ->where('agent_id', $agentId);
            })
                ->orWhere(function ($query) use ($user, $agentId) {
                    $query->where('user_id', $agentId)
                        ->where('agent_id', $user->id);
                })
                ->first();

            if (!$thread) {
                $thread = $this->chatThread::create([
                    'user_id'  => $user->id,
                    'agent_id' => $agentId,
                ]);
            }

            return $this->chatMessage::create([
                'chat_thread_id' => $thread->id,
                'sender_id'      => $user->id,
                'message'        => $chatMessageRequest['message'],
            ]);
        });
    }


    /**
     * جلب جميع الرسائل لمحادثة معينة
     */
    public function getMessages($otherUserId)
    {
        $currentUserId = auth()->id();
        $userId = $otherUserId['user_id'];

        DB::beginTransaction();
        try {
            $thread = $this->chatThread::where(function ($query) use ($currentUserId, $userId) {
                $query->where('user_id', $currentUserId)
                    ->where('agent_id', $userId);
            })
                ->orWhere(function ($query) use ($currentUserId, $userId) {
                    $query->where('user_id', $userId)
                        ->where('agent_id', $currentUserId);
                })
                ->with(['messages.sender'])
                ->first();

            if (!$thread) {
                DB::commit();
                return collect();
            }

            $this->updateMessageIsRead($thread);

            $messages = $thread->messages->load('thread')->sortBy('created_at')->values();

            DB::commit();
            return $messages;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    private function updateMessageIsRead($thread): void
    {
        $thread->messages()
            ->where([
                ['is_read', '=', 0],
                ['chat_thread_id', '=', $thread->id]
            ])
            ->update(['is_read' => true]);
    }

    /**
     * جلب كل المحادثات الخاصة بالمستخدم
     */
    public function getUserThreads()
    {
      return  $this->thread();
    }


}
