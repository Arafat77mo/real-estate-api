<?php

namespace Modules\Properties\App\Transformers;

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
            'description' => $this->description,
            'location' => $this->location,
            'price' => number_format($this->price, 2), // Format price
            'status' => trans('status.' . $this->status),
            'property_images' => $this->getPropertyImagesUrlsAttribute(), // Fetch all images in 'property_images' collection
            'cover_image_url' => $this->getCoverImageUrlAttribute(), // Get the cover image URL
            'created_at' => $this->created_at->toDateString(),
            'updated_at' => $this->updated_at->toDateString(),
        ];
    }
}
