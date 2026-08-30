<?php

namespace App\Http\Requests\Api\Payment;

use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentCallbackRequest extends BaseApiFormRequest
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
            'Authority' => ['required', 'string'],
            'Status' => ['required', 'string', Rule::in(['OK', 'NOK'])],
        ];
    }
}
