<?php

namespace App\Http\Requests\Admin\Content;

use Illuminate\Foundation\Http\FormRequest;

class FAQRequest extends FormRequest
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
            'question' => 'required|max:500|min:3|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,?؟><\/;\n\r& ]+$/u',
            'answer' => 'required|max:600|min:5',
            'status' => 'required|numeric|in:0,1',
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'slug' => 'nullable|unique:faqs,slug',

        ];
    }
}
