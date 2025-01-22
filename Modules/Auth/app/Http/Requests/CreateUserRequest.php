<?php

namespace Modules\Auth\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {

                    return [
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|email|max:255',
                        'password' => 'required|string|min:8',
                        'type' => 'required|in:owner,user', // التحقق من أن الحقل type يحتوي على واحدة من القيم المسموحة
                        'role' => 'required|exists:roles,name',
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
            'email' => __('validation.email'),
            'string' => __('validation.string'),
            'max' => __('validation.max.string'),
            'min' => __('validation.min.string'),
            'unique' => __('validation.unique'),


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
