<?php
namespace Modules\Properties\app\Services\Owner;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Modules\Properties\App\Models\Property;
use Modules\Properties\app\Traits\SaveUserSearch;
use Modules\Properties\app\Traits\SearchableTrait;

class PropertyService
{
    use SearchableTrait, SaveUserSearch;

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
     * Update an existing property.
     *
     * @param Property $property
     * @param array $data
     * @return Property
     */
    public function update(Property $property, array $data): Property
    {
        if ($property->user_id !== auth()->id()) {
            return false;  // العودة بـ false إذا لم يكن المستخدم هو صاحب العقار
        }

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
    public function getById(int $id): false|Property
    {
        // جلب العقار بناءً على الـ ID
        $property = Property::with('media')->findOrFail($id);

        // التحقق إذا كان المستخدم هو صاحب العقار
        if ($property->user_id !== auth()->id()) {
            return false;  // العودة بـ false إذا لم يكن المستخدم هو صاحب العقار
        }

        return $property;
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

        // تصفية العقارات بناءً على التحقق من ملكية العقار
        $query = $query->where('user_id', auth()->id());


        $properties = $query->fastPaginate(10000);

        // تحميل العلاقات (media) بعد الباجنيشن
        $properties->load('media');

        return $properties;
    }

    /**
     * Delete a property by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
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
}
