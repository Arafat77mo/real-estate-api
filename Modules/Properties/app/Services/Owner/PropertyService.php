<?php

namespace Modules\Properties\app\Services\Owner;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Modules\Properties\App\Models\Property;
use Modules\Properties\app\Traits\SearchableTrait;

class PropertyService
{
    use SearchableTrait;

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
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getAllProperties($request): LengthAwarePaginator
    {

        // Start building the query
        $query = Property::search($request['query'] ?? '');

        // Apply filters
        $query = $this->applyFilters($query, $request);

        $properties= $query->fastPaginate(10000);

        // تحميل العلاقة 'media' بعد البجنيشن
        $properties->load('media');

        return $properties;
    }

    public function delete(int $id)
    {
        // جلب العقار بناءً على الـ ID
        $property = Property::findOrFail($id);

        // التحقق إذا كان المستخدم هو صاحب العقار
        if ($property->user_id !== auth()->id()) {
            return false;
        }
        // مسح جميع الصور المرتبطة بالعقار
        $property->clearMediaCollection('property_images');

        // حذف العقار
        $property->delete();

        return true;
    }

    public function authorize($action, $property)
    {
        $user = auth()->user(); // Ensure you're getting the currently authenticated user
        return Gate::allows($action, $property);    }

}
