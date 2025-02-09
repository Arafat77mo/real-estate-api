<?php

namespace Modules\Properties\app\Services\Admin;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Modules\Properties\App\Models\Property;
use Modules\Properties\app\Traits\SearchableTrait;

class PropertyService
{
    use SearchableTrait;

    protected $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }


    /**
     * Get a property by its ID.
     *
     * @param int $id
     * @return Property
     */
    public function getById(int $id): Property
    {
        return $this->property::with('media')->findOrFail($id);
    }

    /**
     * Get all properties.
     *
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getAllProperties($request): LengthAwarePaginator
    {

        return Cache::remember("user_properties_{$this->auth->id()}", 3600, function () use ($request) {


            // Start building the query
        $query = $this->property::search($request['query'] ?? '');

        // Apply filters
        $query = $this->applyFilters($query, $request);

        $properties= $query->fastPaginate(10000);

        // تحميل العلاقة 'media' بعد البجنيشن
        $properties->load('media');

        return $properties;

        });

    }





}
