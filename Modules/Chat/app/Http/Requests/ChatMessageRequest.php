<?php

namespace Modules\Chat\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'message'        => 'required|string|max:1000',
            'agent_id'    => 'required|exists:users,id',

        ];
    }


    public function messages(): array
    {
        return [

            'message.required'         => 'يجب إدخال الرسالة.',
            'message.max'              => 'يجب ألا تتجاوز الرسالة 1000 حرف.',
            'agent_id.required'    => 'يجب تحديد معرف الوكيل.',
            'agent_id.exists'      => 'الوكيل المحدد غير موجود.',     // يجب أن يكون معرف الوكيل موجود في جدول المستخدمين

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
