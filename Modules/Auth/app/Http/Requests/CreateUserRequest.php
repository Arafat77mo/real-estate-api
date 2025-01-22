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
        switch ($this->method()) {
            case 'POST':
                if ($this->is('api/register')) {
                    return [
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|email|max:255|unique:users',
                        'password' => 'required|string|min:8',
                        'type' => 'required|in:owner,user', // التحقق من أن الحقل type يحتوي على واحدة من القيم المسموحة

                    ];
                } elseif ($this->is('api/login')) {
                    return [
                        'email' => 'required|email',
                        'password' => 'required|string',
                    ];
                } elseif ($this->is('api/password/email')) {
                    return [
                        'email' => 'required|email',
                    ];
                } elseif ($this->is('api/password/reset')) {
                    return [
                        'token' => 'required|string',
                        'email' => 'required|email',
                        'password' => 'required|string|min:8|confirmed',
                    ];
                }
                break;

            default:
                return [];
        }
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
