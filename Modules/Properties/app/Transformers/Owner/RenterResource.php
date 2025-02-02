<?php

namespace Modules\Properties\app\Transformers\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'user_name' => $this->user->name,
            'paid_amount' => $this->paid_amount,
            'pending_amount' => $this->pending_amount,
            'paid_months' => $this->paid_months,
        ];

    }

}
