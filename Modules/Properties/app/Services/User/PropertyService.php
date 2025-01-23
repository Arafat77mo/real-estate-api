<?php

namespace Modules\Properties\app\Services\User;

use App\Models\UserSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Modules\Properties\App\Models\Property;
use Modules\Properties\app\Traits\SaveUserSearch;
use Modules\Properties\app\Traits\SearchableTrait;

class PropertyService
{
    use SearchableTrait,SaveUserSearch;


    /**
     * Get a property by its ID.
     *
     * @param int $id
     * @return Property
     */
    public function getById(int $id): Property
    {
        return Property::with('media','owner')->findOrFail($id);
    }

    /**
     * Get all properties.
     *
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getAllProperties($request): LengthAwarePaginator
    {
        $query = Property::search($request['query'] ?? '');

        // Apply filters
        $query = $this->applyFilters($query, $request);


        $this->saveUserSearch($request);

        $properties = $query->fastPaginate(50000);

        $properties->load('media', 'owner');

        return $properties;
    }



    public function recommendProperties()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 403);
        }

        // Fetch the user's last search data
        $lastSearch = UserSearch::query()->where('user_id', auth()->id())->latest()->first();

        if (!$lastSearch) {
            return response()->json(['message' => 'No search history found'], 404);
        }

        // Ensure filters are stored as an array
        $filters = $lastSearch->filters ?? [];


        // Apply filters using a separate method
        return $this->applyUsersFilters($filters)->load('media', 'owner');
    }








}
