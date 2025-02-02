<?php

namespace Modules\Transactions\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'transaction_type' => 'required|in:sale,rent,installment',
            'price' => 'required|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'payment_method' => 'required|in:stripe,tamara,tabby',
            'payment_method_id' => 'required_if:payment_method,stripe',
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
