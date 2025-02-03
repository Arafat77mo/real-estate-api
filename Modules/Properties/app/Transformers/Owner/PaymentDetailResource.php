<?php

namespace Modules\Properties\app\Transformers\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'payment_id' => $this->id,
            'amount' => $this->amount,
            'due_date' => $this->due_date,
            'status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'transaction_reference' => $this->transaction_id,

        ];

    }

}
