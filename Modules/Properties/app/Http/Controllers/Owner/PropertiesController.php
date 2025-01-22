<?php

namespace Modules\Properties\app\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Properties\App\Helpers\ResponseData;
use Modules\Properties\App\Http\Requests\CreatePropertyRequest;
use Modules\Properties\App\Http\Requests\CreatePropertySearchRequest;
use Modules\Properties\app\Http\Requests\UpdatePropertyRequest;
use Modules\Properties\app\Services\Owner\PropertyService;
use Modules\Properties\app\Transformers\Owner\PropertyResource;
use Modules\Properties\app\Transformers\Owner\ShowPropertyResource;


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
                ['user_id' =>  $user->id] // Assign the authenticated user's ID to the property
            ));
            return ResponseData::send('success', trans('properties.success.created'), new ShowPropertyResource($property));

    }

    public function update(UpdatePropertyRequest $request, int $id): JsonResponse
    {
        try {
            // الحصول على العقار بناءً على الـ ID
            $property = $this->propertyService->getById($id);


            // تمرير البيانات المحدثة إلى خدمة الـ Property
            $updatedProperty = $this->propertyService->update($property, $request->validated());

            return ResponseData::send('success', trans('properties.success.updated'), new ShowPropertyResource($updatedProperty));
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
            return ResponseData::send('success', trans('properties.success.found'), new ShowPropertyResource($property));
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
    public function index(CreatePropertySearchRequest  $request): JsonResponse
    {
        $validated = $request->validated();


        $properties = $this->propertyService->getAllProperties($validated);
        return ResponseData::send('success', trans('properties.success.list_retrieved'), PropertyResource::collection($properties)->withQueryString());
    }

    public function delete($id)
    {
        try {
            $property = $this->propertyService->delete($id);
            if ($property) {
                return ResponseData::send('success', trans('properties.success.deleted'));
            }
            else{
                return ResponseData::send('error', trans('properties.error.authorization'));

            }
        } catch (\Exception $e) {
            return ResponseData::send('error', trans('properties.error.deleted'), [
                'error' => $e->getMessage(),
            ]);
        }
    }

}
