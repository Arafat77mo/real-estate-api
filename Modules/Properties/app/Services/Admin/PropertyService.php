<?php

namespace Modules\Properties\app\Services\Admin;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Modules\Properties\App\Models\Property;
use Modules\Properties\app\Traits\SearchableTrait;

class PropertyService
{
    use SearchableTrait;


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





}
