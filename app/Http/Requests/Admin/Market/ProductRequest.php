<?php

namespace App\Http\Requests\Admin\Market;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'name' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
                'category_id' => 'required|min:1|max:100000000|regex:/^[0-9]+$/u|exists:product_categories,id',
                'brand_id' => 'required|min:1|max:100000000|regex:/^[0-9]+$/u|exists:brands,id',
                'image' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048',
                'status' => 'required|in:draft,published',

                'tags' => 'required|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
                'description' => 'required|max:600|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
                'published_at' => 'nullable|date_format:Y-m-d H:i',
                'base_price' => 'required|numeric',
            ];
        } else {
            return [
                'name' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
                'category_id' => 'required|min:1|max:100000000|regex:/^[0-9]+$/u|exists:product_categories,id',
                'brand_id' => 'required|min:1|max:100000000|regex:/^[0-9]+$/u|exists:brands,id',
                'image' => 'image|mimes:png,jpg,jpeg,gif|max:2048',
                'status' => 'required|in:draft,published',

                'tags' => 'required|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
                'description' => 'required|max:600|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
                'published_at' => 'nullable|date_format:Y-m-d H:i',
                'base_price' => 'required|numeric',
            ];
        }
    }
}
