<?php

namespace Modules\Chat\App\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $currentUser = Auth::id();
        return [
            'id'             => $this->id,
            'user_id' => $this->thread->user_id,
            'agent_id' => $this->thread->agent_id,
            'chat_thread_id' => $this->chat_thread_id,
            'message'        => $this->message,
            'sender'         => [
                'id'       => $this->sender->id,
                'name'     => $this->sender->name,
            ],
            'is_me'          => $this->sender_id === $currentUser,
            'created_at'     => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
