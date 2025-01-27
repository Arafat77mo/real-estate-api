<?php

namespace Modules\Favorite\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Favorite\App\Helpers\ResponseData;
use Modules\Favorite\App\Http\Requests\CreateFavorite;
use Modules\Favorite\app\Services\FavoriteService;
use Modules\Favorite\App\Transformers\FavoriteResource;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    /**
     * تشغيل/إيقاف العنصر في المفضلة.
     */
    public function toggleFavorite(CreateFavorite $request)
    {
        try {
            $result = $this->favoriteService->toggleFavorite(auth()->id(), $request->property_id);

            return ResponseData::send($result['status'], __($result['message']));
        } catch (\Exception $e) {
            return ResponseData::send('error', __('favorite.error_occurred'));
        }
    }

    public function index()
    {
        $favorites = $this->favoriteService->getUserFavorites(auth()->id());

        return ResponseData::send('success', __('favorite.list'), FavoriteResource::collection($favorites));
    }
}
