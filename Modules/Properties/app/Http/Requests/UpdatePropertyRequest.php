<?php

namespace Modules\Properties\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|array', // Since it's translatable, it should be an array
            'name.en' => 'required|string|max:255', // English name
            'name.ar' => 'required|string|max:255', // Arabic name
            'description' => 'required|array', // Since it's translatable, it should be an array
            'description.en' => 'required|string', // English description
            'description.ar' => 'required|string', // Arabic description
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'property_images' => 'sometimes|array', // Not required: It is only checked if provided
            'property_images.*' => 'image|mimes:jpeg,png|max:2048', // Image validation (if provided)
        ];


    }

    public function messages(): array
    {
        return [
            'required' => trans('validation.required'),
            'string' => trans('validation.string'),
            'max' => trans('validation.max'),
            'numeric' => trans('validation.numeric'),
            'in' => trans('validation.in'),
            'array' => trans('validation.array'),
            'mimes' => trans('validation.mimes'),
            'image' => trans('validation.image'),
            'property_images.*.image' => trans('validation.image'),
            'property_images.*.mimes' => trans('validation.mimes'),
            'property_images.*.max' => trans('validation.max_size'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => trans('validation.attributes.name'),
            'description' => trans('validation.attributes.description'),
            'location' => trans('validation.attributes.location'),
            'price' => trans('validation.attributes.price'),
            'status' => trans('validation.attributes.status'),
            'property_images' => trans('validation.attributes.property_images'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
