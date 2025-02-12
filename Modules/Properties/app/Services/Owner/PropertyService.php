<?php
namespace Modules\Properties\App\Services\Owner;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Properties\App\Models\Property;
use Modules\Properties\App\Traits\SaveUserSearch;
use Modules\Properties\App\Traits\SearchableTrait;

class PropertyService
{
    use SearchableTrait, SaveUserSearch;

    protected $auth;
    protected $property;

    public function __construct(Guard $auth, Property $property)
    {
        $this->auth = $auth;
        $this->property = $property;
    }

    /**
     * إنشاء عقار جديد
     */
    public function create(array $data): Property
    {
        $property = $this->property->create($data);

        // رفع الصور بعد إنشاء العقار
        if (!empty($data['property_images'])) {
            foreach ($data['property_images'] as $image) {
                $property->addMedia($image)->toMediaCollection('property_images');
            }
        }

        // حذف الكاش عند إضافة عقار جديد
        Cache::forget("user_properties_{$this->auth->id()}");

        return $property;
    }

    /**
     * تحديث بيانات العقار
     */
    public function update($id, array $data): false|Property
    {
        $property=$this->property->findOrFail($id);

        $property->update($data);

        // تحديث الصور
        if (!empty($data['property_images'])) {
            $property->clearMediaCollection('property_images');
            foreach ($data['property_images'] as $image) {
                $property->addMedia($image)->toMediaCollection('property_images');
            }
        }

        // تحديث الكاش
        Cache::forget("property_{$property->id}");
        Cache::forget("user_properties_{$this->auth->id()}");

        return $property;
    }

    /**
     * جلب العقار بناءً على الـ ID مع استخدام Redis
     */
    public function getById(int $id): Property
    {
            return $this->property->with('media')->findOrFail($id);

    }

    /**
     * جلب جميع العقارات الخاصة بالمستخدم مع Redis
     */
    public function getAllProperties($request)
    {

            $query = $this->property->search($request['query'] ?? '')
                ->where('user_id', $this->auth->id());

            $properties = $query->fastPaginate(100);
            // تحميل العلاقات (media) بعد الباجنيشن
            $properties->load('media');

            return $properties;

    }

    /**
     * حذف العقار مع حذف الكاش
     */
    public function delete(int $id): bool
    {
        $property = $this->property->findOrFail($id);

        if ($this->auth->id() !== $property->user_id) {
            return false;
        }

        $property->clearMediaCollection('property_images');
        $property->delete();

        // حذف الكاش عند حذف العقار
        Cache::forget("property_{$id}");
        Cache::forget("user_properties_{$this->auth->id()}");

        return true;
    }
}
