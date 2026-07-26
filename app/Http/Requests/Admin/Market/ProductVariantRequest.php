<?php

namespace App\Http\Requests\Admin\Market;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantRequest extends FormRequest
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
            $product = $this->route('product');

            $rules = [
                'price' => ['required', 'numeric', 'min:1'],
            ];

            // ===== COLOR =====
            if ($product->has_color) {
                $rules['colors'] = ['required', 'array', 'min:1'];
                $rules['colors.*'] = ['integer', 'exists:product_colors,id'];
            } else {
                $rules['colors'] = ['prohibited'];
            }

            // ===== SIZE =====
            if ($product->has_size) {
                $rules['sizes'] = ['required', 'array', 'min:1'];
                $rules['sizes.*'] = ['integer', 'exists:product_sizes,id'];
            } else {
                $rules['sizes'] = ['prohibited'];
            }

            return $rules;
        }
        return [
            'price' => ['required', 'numeric', 'min:1'],
            'colors' => ['prohibited'],
            'sizes'  => ['prohibited'],
        ];
    }
}
