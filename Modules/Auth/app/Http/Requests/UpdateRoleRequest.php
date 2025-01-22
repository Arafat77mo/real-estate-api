<?php

namespace Modules\Auth\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->route('role'),
            'permissions' => 'required|array', // التأكد من أن البرميشنات هي مصفوفة
            'permissions.*' => 'exists:permissions,id', // التأكد من أن كل برميشن موجود في جدول البرميشنات
        ];
    }

}
