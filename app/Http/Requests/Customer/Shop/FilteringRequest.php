<?php

namespace App\Http\Requests\Customer\Shop;

use Illuminate\Foundation\Http\FormRequest;

class FilteringRequest extends FormRequest
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
            'search' => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|gte:min_price',
            'brands' => 'nullable|array',
            'brands.*' => 'exists:brands,slug',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:product_colors,slug',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:product_sizes,slug',
            'sort' => 'nullable|in:trending,best_selling,top_rated,newness,cheapest,most_expensive',
            'in_stock' => 'nullable|boolean',
            'out_of_stock' => 'nullable|boolean',
            'on_sale' => 'nullable|boolean',
            'big_deals' => 'nullable|boolean',
            'tag' => 'nullable|exists:tags,slug',
        ];
    }
}
