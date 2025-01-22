<?php

namespace Modules\Properties\app\Transformers\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'price' => number_format($this->price, 2), // Format price with two decimal places
            'status' => trans('status.' . $this->status), // Translate the status
            'cover_image_url' => $this->whenLoaded('media', function () {
                return $this->getCoverImageUrlAttribute();
            }),
            'created_at' => $this->created_at->toDateString(), // Format created_at date
            'updated_at' => $this->updated_at->toDateString(), // Format updated_at date

        ];

    }

}
