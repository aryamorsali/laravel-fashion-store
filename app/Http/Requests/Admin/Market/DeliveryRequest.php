<?php

namespace App\Http\Requests\Admin\Market;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:120|regex:/^[ا-یa-zA-Z0-9۰-۹ء-ي.,\-\s]+$/u',
            'delivery_cost' => 'nullable|numeric|min:0',
            'delivery_days' => 'nullable|integer|min:1',
            'status' => 'required|in:0,1',
        ];
    }
}
