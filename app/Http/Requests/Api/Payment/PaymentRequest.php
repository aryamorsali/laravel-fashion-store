<?php

namespace App\Http\Requests\Api\Payment;

use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends BaseApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_id' => 'required|exists:addresses,id',
            'delivery_id' => 'required|exists:deliveries,id',
            'coupon' => 'nullable|max:120|min:2'
        ];
    }
}
