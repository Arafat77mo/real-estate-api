<?php

namespace Modules\Properties\app\Transformers\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'user_name' => $this->user->name,
            'paid_amount' => $this->price,
            'property' => $this->property_id,
            'is_paid' => $this->is_paid,
        ];

    }

}
