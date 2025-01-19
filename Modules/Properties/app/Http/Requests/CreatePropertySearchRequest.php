<?php

namespace Modules\Properties\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePropertySearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'query' => 'nullable|string',
            'location' => 'nullable|string',
            'type' => 'nullable|in:apartment,villa,land,office,commercial',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
            'rooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'sort_by' => 'nullable|in:price,name,location,rooms,bathrooms',
            'sort_direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
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
