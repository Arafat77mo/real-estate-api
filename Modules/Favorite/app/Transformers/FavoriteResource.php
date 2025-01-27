<?php

namespace Modules\Favorite\App\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Properties\app\Transformers\PropertyResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'property' =>new PropertyResource($this->property),
            'created_at' => $this->created_at,
        ];
    }
}
