<?php

namespace App\Http\Requests\Admin\Market;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseVariantRequest extends FormRequest
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
    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'product_variant_id' => 'required|integer|exists:product_variants,id',
                'stock' => 'required|integer|min:1|max:10000',
            ];
        } else {
            return [
                'stock' => 'required|integer|min:0|max:10000',
            ];
        }
    }
}
