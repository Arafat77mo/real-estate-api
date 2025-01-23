<?php
namespace Modules\Properties\app\Traits;

use App\Models\UserSearch;

trait SaveUserSearch
{
    /**
     * حفظ عملية البحث.
     *
     * @param array $data
     * @return void
     */
    public function saveUserSearch(array $data): void
    {
        // حفظ عملية البحث فقط إذا كان المستخدم مسجل الدخول
        if (auth()->check()) {
            UserSearch::create([
                'user_id' => auth()->id(),
                'search_query' => $data['query'] ?? null,
                'filters' => [
                    'location' => $data['location'] ?? null,
                    'type' => $data['type'] ?? null,
                    'min_price' => $data['min_price'] ?? null,
                    'max_price' => $data['max_price'] ?? null,
                    'rooms' => $data['rooms'] ?? null,
                    'bathrooms' => $data['bathrooms'] ?? null,
                ],
            ]);
        }
    }
}
