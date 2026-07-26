<?php

namespace App\Http\Requests\Admin\Market;

use App\Models\Market\HomeBox;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $homeBoxId = $this->route('homeBox')->id ?? null;
        return [
            'title' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
            'subtitle' => 'nullable|max:150|min:3|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
            'image' => 'required|image|max:4096|dimensions:min_width=1200,min_height=770',
            'position' => [
                'required',
                Rule::in(HomeBox::$positions),
                Rule::unique('home_boxes', 'position')->ignore($homeBoxId)
            ],

            'status' => 'required|numeric|in:0,1',
            'category_id' => 'required|numeric|exists:product_categories,id',
        ];
    }
}
