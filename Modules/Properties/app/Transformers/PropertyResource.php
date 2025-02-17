<?php

namespace Modules\Properties\app\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\app\Transformers\UserResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'name' => $this->name,
            'location' => $this->location,
            'price' => number_format($this->price, 2), // Format price with two decimal places
            'cover_image_url' => $this->whenLoaded('media', function () {
                return $this->getCoverImageUrlAttribute();
            }),

        ];

    }

}
