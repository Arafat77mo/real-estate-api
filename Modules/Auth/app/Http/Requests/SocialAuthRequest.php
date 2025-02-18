<?php

namespace Modules\Auth\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'provider' => 'required|in:google,facebook,github',
        ];
    }

    /**
     * Define custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            // Common validation messages
            'required' => __('validation.required'),
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
