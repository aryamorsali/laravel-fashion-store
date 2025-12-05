<?php

namespace App\Http\Requests\Admin\Market;

use Illuminate\Foundation\Http\FormRequest;

class HomeBoxRequest extends FormRequest
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
            'title' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
            'subtitle' => 'nullable|max:150|min:3|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
            'image' => 'nullable|image|max:4096|dimensions:min_width=1200,min_height=770',

            'status' => 'required|numeric|in:0,1',
            'category_id' => 'nullable|numeric|exists:product_categories,id',
            'url' => 'nullable|min:5|max:500|url',
        ];
    }
}
