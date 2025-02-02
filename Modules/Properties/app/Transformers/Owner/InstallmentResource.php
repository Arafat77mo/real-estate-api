<?php

namespace Modules\Properties\app\Transformers\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstallmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'paid_months' => $this->paid_months,
            'remaining_months' => $this->remaining_months,
            'next_due_date' => $this->next_due_date,
        ];

    }

}
