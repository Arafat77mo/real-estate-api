<?php

namespace Modules\Properties\app\Traits;

use Laravel\Scout\Builder;
use Modules\Properties\app\Enums\Filter;
use Modules\Properties\app\Filters\BathroomsFilter;
use Modules\Properties\app\Filters\LocationFilter;
use Modules\Properties\app\Filters\MaxPriceFilter;
use Modules\Properties\app\Filters\PriceFilter;
use Modules\Properties\app\Filters\RoomsFilter;
use Modules\Properties\app\Filters\TypeFilter;
use Modules\Properties\App\Models\Property;

trait SearchableTrait
{
    /**
     * تطبيق الفلاتر على الاستعلام بناءً على المعاملات الواردة في الفلاتر.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        // المرور عبر الفلاتر المدخلة وتطبيق الفلتر الصحيح
        foreach ($filters as $key => $value) {
            // التحقق مما إذا كان المفتاح موجودًا في الـ Enum
            if ($filter = Filter::tryFrom($key)) {
                $this->applyFilter($query, $filter, $value);
            }
        }

        return $query;
    }

    /**
     * تطبيق الفلتر بناءً على المفتاح والقيمة المحددة.
     *
     * @param Builder $query
     * @param Filter $filter
     * @param mixed $value
     */
    protected function applyFilter(Builder $query, Filter $filter, $value): void
    {
        $filterClass = $this->getFilterClass($filter);

        if ($filterClass) {
            $filterClass->apply($query, $value);
        }
    }

    /**
     * استرجاع فئة الفلتر بناءً على الفلتر المدخل.
     *
     * @param Filter $filter

     */
    protected function getFilterClass(Filter $filter)
    {
        switch ($filter) {
            case Filter::LOCATION:
                return new LocationFilter();
            case Filter::TYPE:
                return new TypeFilter();
            case Filter::ROOMS:
                return new RoomsFilter();
            case Filter::BATHROOMS:
                return new BathroomsFilter();
            case Filter::MIN_PRICE:
                return new PriceFilter();
            case Filter::MAX_PRICE:
                return new MaxPriceFilter();
            default:
                return null;
        }
    }

    protected function applyUsersFilters($request)
    {

        // Build the query with conditional filters
        $query = Property::query();

        if (!empty($request['location'])) {
            $query->where('location', 'LIKE', '%' . $request['location'] . '%');
        }

        if (!empty($request['type'])) {
            $query->where('type', $request['type']);
        }

        if (!empty($request['min_price'])) {
            $query->where('price', '>=', $request['min_price']);
        }

        if (!empty($request['max_price'])) {
            $query->where('price', '<=', $request['max_price']);
        }

        if (!empty($request['rooms'])) {
            $query->where('rooms', $request['rooms']);
        }

        if (!empty($request['bathrooms'])) {
            $query->where('bathrooms', $request['bathrooms']);
        }

        return $query->limit(10)->get();

    }

}
