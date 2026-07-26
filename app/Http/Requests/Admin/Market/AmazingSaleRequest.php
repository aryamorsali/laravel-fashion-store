<?php

namespace App\Http\Requests\Admin\Market;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AmazingSaleRequest extends FormRequest
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
                'percentage' => ['required', 'numeric', 'min:1', 'max:100'],
                'product_id' => ['required', 'exists:products,id'],

                'product_variant_ids' => ['required', 'array', 'min:1'],

                'product_variant_ids.*' => [
                    'required',
                    Rule::exists('product_variants', 'id')
                        ->where(
                            fn($q) =>
                            $q->where('product_id', $this->product_id)
                        ),
                ],

                'is_active' => ['required', 'boolean'],

                'start_date' => ['required', 'date', 'after_or_equal:today'],
                'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            ];
        } else {
            return [

                'percentage' => ['required', 'numeric', 'min:1', 'max:100'],
                'is_active' => ['required', 'boolean'],
                'start_date' => ['required', 'date'],
                'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            ];
        }
    }
}
