<?php

namespace Modules\Favorite\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFavorite extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'property_id' => 'required|integer|exists:properties,id',
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
