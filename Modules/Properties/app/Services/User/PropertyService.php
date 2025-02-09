<?php

namespace Modules\Properties\App\Services\User;

use App\Models\UserSearch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Properties\App\Models\Property;
use Modules\Properties\App\Traits\SaveUserSearch;
use Modules\Properties\App\Traits\SearchableTrait;

class PropertyService
{
    use SearchableTrait, SaveUserSearch;

    protected $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    /**
     * Get a property by its ID (with caching)
     */
    public function getById(int $id): Property
    {
        return Cache::remember("property_{$id}", 500, function () use ($id) {
            return $this->property->with('media', 'owner')->findOrFail($id);
        });
    }

    /**
     * Get all properties with caching.
     */
    public function getAllProperties($request): LengthAwarePaginator
    {
        return Cache::remember("all_properties_" . md5(json_encode($request)), 500, function () use ($request) {
            $query = $this->property->search($request['query'] ?? '');
            $query = $this->applyFilters($query, $request);

            $this->saveUserSearch($request);

            $properties = $query->fastPaginate(50000);
            $properties->load('media', 'owner');

            return $properties;
        });
    }

    /**
     * Recommend properties based on user's last search
     */
    public function recommendProperties()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 403);
        }

        $userId = auth()->id();

        $lastSearch = Cache::remember("last_search_{$userId}", 3600, function () use ($userId) {
            return UserSearch::where('user_id', $userId)->latest()->first();
        });

        if (!$lastSearch) {
            return response()->json(['message' => 'No search history found'], 404);
        }

        $filters = $lastSearch->filters ?? [];

        return $this->applyUsersFilters($filters)->load('media', 'owner');
    }
}
