<?php

namespace Modules\Properties\App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Properties\App\Models\Property;
use function Laravel\Prompts\search;
use Hammerstone\FastPaginate\FastPaginate;

class PropertyService
{
    /**
     * Create a new property.
     *
     * @param array $data
     * @return Property
     */
    public function create(array $data): Property
    {
        $property = Property::create($data);

        // رفع الصور بعد إنشاء العقار
        if (isset($data['property_images'])) {
            foreach ($data['property_images'] as $image) {
                $property->addMedia($image)->toMediaCollection('property_images');
            }
        }

        return $property;
    }

    /**
     * تحديث العقار.
     *
     * @param Property $property
     * @param array $data
     * @return Property
     */
    public function update(Property $property, array $data): Property
    {
        $property->update($data);

        // رفع الصور إذا كانت موجودة
        if (isset($data['property_images'])) {
            $property->clearMediaCollection('property_images');

            foreach ($data['property_images'] as $image) {
                $property->addMedia($image)->toMediaCollection('property_images');
            }
        }

        return $property;
    }

    /**
     * Get a property by its ID.
     *
     * @param int $id
     * @return Property
     */
    public function getById(int $id): Property
    {
        return Property::with('media')->findOrFail($id);
    }

    /**
     * Get all properties.
     *
     * @return Collection
     */
    public function getAllProperties($query): LengthAwarePaginator
    {
        // استخدام fastPaginate
        $properties = Property::search($query)->fastPaginate(50);

        // تحميل العلاقة 'media' بعد البجنيشن
        $properties->load('media');

        return $properties;
    }

}
