<?php

namespace Modules\Properties\app\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Properties\App\Helpers\ResponseData;
use Modules\Properties\App\Http\Requests\CreatePropertySearchRequest;
use Modules\Properties\app\Services\User\PropertyService;
use Modules\Properties\app\Transformers\PropertyResource;

class PropertiesController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {

        $this->propertyService = $propertyService;
    }


    /**
     * Show the details of a property.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $property = $this->propertyService->getById($id);
            return ResponseData::send('success', trans('properties.success.found'), new PropertyResource($property));
        } catch (Exception $e) {
            return ResponseData::send('error', trans('properties.error.not_found'), [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get a list of properties.
     *
     * @return JsonResponse
     */
    public function index(CreatePropertySearchRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();


            $properties = $this->propertyService->getAllProperties($validated);
            return ResponseData::send('success', trans('properties.success.list_retrieved'), PropertyResource::collection($properties)->withQueryString());

        } catch (Exception $e) {
            return ResponseData::send('error', trans('properties.error.not_found'), [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function recommendProperties()
    {
        try {
            $properties = $this->propertyService->recommendProperties();
            return ResponseData::send('success', trans('properties.success.list_retrieved'), PropertyResource::collection($properties));
        } catch (Exception $e) {
            return ResponseData::send('error', trans('properties.error.not_found'), [
                'error' => $e->getMessage(),
            ]);
        }
    }


}
