<?php

namespace Modules\Chat\App\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'participant'  => $this->participant,
            'last_message' => $this->last_message,
            'unread_count' => $this->unread_count,
            'updated_at'   => $this->updated_at,
        ];

    }
}
