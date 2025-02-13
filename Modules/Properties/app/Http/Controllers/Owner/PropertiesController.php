<?php

namespace Modules\Properties\app\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Exception;
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
            ['user_id' => $user->id] // Assign the authenticated user's ID to the property
        ));
        return ResponseData::send(trans('messages.success'), trans('properties.success.created'), new ShowPropertyResource($property));

    }

    public function update(UpdatePropertyRequest $request,  $id): JsonResponse
    {

            // تمرير البيانات المحدثة إلى خدمة الـ Property
            $updatedProperty = $this->propertyService->update($id,$request->validated());

            return ResponseData::send(trans('messages.success'), trans('properties.success.updated'), new ShowPropertyResource($updatedProperty));

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
            if ($property) {
                return ResponseData::send(trans('messages.success'), trans('properties.success.found'),  new ShowPropertyResource($property));

            }

            return ResponseData::send(trans('messages.error'), trans('properties.error.not_found')
            );
        } catch (Exception $e) {
            return ResponseData::send(trans('messages.error'), trans('properties.error.not_found'), [
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
        return ResponseData::send(trans('messages.success'), trans('properties.success.list_retrieved'), PropertyResource::collection($properties)->withQueryString());
        } catch (Exception $e) {
            return ResponseData::send(trans('messages.error'), trans('properties.error.not_found'), [
                'error' => $e->getMessage(),
            ]);
        }


    }

    public function delete($id)
    {
        try {
            $property = $this->propertyService->delete($id);
            if ($property) {
                return ResponseData::send(trans('messages.success'), trans('properties.success.deleted'));
            } else {
                return ResponseData::send(trans('messages.error'), trans('properties.error.authorization'));

            }
        } catch (Exception $e) {
            return ResponseData::send(trans('messages.error'), trans('properties.error.deleted'), [
                'error' => $e->getMessage(),
            ]);
        }
    }

}
