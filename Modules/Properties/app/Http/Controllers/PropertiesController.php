<?php

namespace Modules\Properties\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Properties\app\Http\Requests\UpdatePropertyRequest;
use Modules\Properties\App\Services\PropertyService;
use Modules\Properties\App\Http\Requests\CreatePropertyRequest;
use Modules\Properties\App\Transformers\PropertyResource;
use Modules\Properties\App\Helpers\ResponseData;
use Illuminate\Http\JsonResponse;

class PropertiesController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * Store a new property.
     *
     * @param CreatePropertyRequest $request
     * @return JsonResponse
     */
    public function store(CreatePropertyRequest $request): JsonResponse
    {

            $user = auth()->user();

            $property = $this->propertyService->create(array_merge(
                $request->validated(),
                ['user_id' =>  2] // Assign the authenticated user's ID to the property
            ));
            return ResponseData::send('success', trans('properties.success.created'), new PropertyResource($property));

    }

    public function update(UpdatePropertyRequest $request, int $id): JsonResponse
    {
        try {
            // الحصول على العقار بناءً على الـ ID
            $property = $this->propertyService->getById($id);


            // تمرير البيانات المحدثة إلى خدمة الـ Property
            $updatedProperty = $this->propertyService->update($property, $request->validated());

            return ResponseData::send('success', trans('properties.success.updated'), new PropertyResource($updatedProperty));
        } catch (\Exception $e) {
            return ResponseData::send('error', trans('properties.error.update_failed'), [
                'error' => $e->getMessage(),
            ]);
        }
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
        } catch (\Exception $e) {
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
    public function index(Request $request): JsonResponse
    {
        $query = $request->input('q');  // جلب النص المُدخل من المستخدم

        $properties = $this->propertyService->getAllProperties($query);
        return ResponseData::send('success', trans('properties.success.list_retrieved'), PropertyResource::collection($properties)->withQueryString());
    }
}
