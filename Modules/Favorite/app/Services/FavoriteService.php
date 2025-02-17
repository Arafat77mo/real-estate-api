<?php
namespace Modules\Favorite\app\Services;

use Modules\Favorite\app\Models\Favorite;

class FavoriteService
{
    protected  function __construct(protected Favorite $favorite)
    {

    }
    /**
     * تشغيل/إيقاف العنصر في المفضلة.
     */
    public function toggleFavorite($userId, $propertyId)
    {
        $favorite = $this->favorite::firstOrNew(['user_id' => $userId, 'property_id' => $propertyId]);

        if ($favorite->exists) {
            $favorite->delete();
            return ['status' => 'removed', 'message' => 'favorite.removed_from_favorites'];
        }

        $favorite->save();
        return ['status' => 'added', 'message' => 'favorite.added_to_favorites'];
    }



    public function getUserFavorites($userId)
    {
        return $this->favorite::with(['property.media', 'property.owner']) // Load property media and owner
        ->where('user_id', $userId)
            ->fastPaginate(10); // Limit results per page
    }


}
