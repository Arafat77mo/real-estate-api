<?php

namespace Modules\Properties\app\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\app\Transformers\UserResource;

class ShowPropertyResource extends JsonResource
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
            'description' => $this->description,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'price' => number_format($this->price, 2), // Format price with two decimal places
            'status' => trans('status.' . $this->status), // Translate the status
            'property_images' => $this->whenLoaded('media', function () {
                return $this->getPropertyImagesUrlsAttribute();
            }),
            'cover_image_url' => $this->whenLoaded('media', function () {
                return $this->getCoverImageUrlAttribute();
            }),
            'rooms' => $this->rooms, // عدد الغرف
            'bathrooms' => $this->bathrooms, // عدد الحمامات
            'living_room_size' => $this->living_room_size ? number_format($this->living_room_size, 2) : null, // حجم غرفة المعيشة (nullable)
            'additional_features' => $this->additional_features, // الميزات الإضافية (اختياري)
            'type' => trans('property_types.' . $this->type), // Use translated property types
            'created_at' => $this->created_at->toDateString(), // Format created_at date
            'updated_at' => $this->updated_at->toDateString(), // Format updated_at date

        ];

    }

}
