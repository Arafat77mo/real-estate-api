<?php

namespace Modules\Chat\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatThreadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'chat_thread_id' => 'required|exists:chat_threads,id', // يجب أن يكون معرف المحادثة موجود في جدول chat_threads
            'user_id' => 'required|exists:users,id', // يجب أن يكون معرف المحادثة موجود في جدول chat_threads

        ];
    }


    public function messages(): array
    {
        return [
            'chat_thread_id.required' => 'يجب تحديد معرف المحادثة.',
            'chat_thread_id.exists'   => 'المحادثة المحددة غير موجودة.',
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
