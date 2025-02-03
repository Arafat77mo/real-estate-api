<?php

namespace Modules\Properties\app\Transformers\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'transaction_id' => $this->id,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'email' => $this->user->email
            ],
            'property' => [
                'id' => $this->property_id,
                'name' => $this->property->name,
                'location' => $this->property->location
            ],
            'financials' => [
                'total_price' => $this->price,
                'paid_amount' => $this->is_paid ? $this->price : 0,
                'payment_status' => $this->is_paid ? 'paid' : 'pending',
                'payment_date' => $this->created_at->toIso8601String()
            ],
        ];

    }

}
