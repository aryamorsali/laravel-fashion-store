<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        $rules = [];
        $key = $this->input('key');
        if ($key === 'site_logo') {
            $rules['value'] = 'nullable|image|mimes:jpg,jpeg,png|max:4096';
        } else {
            $rules['value'] = 'nullable|string|max:255';
        }
        return $rules;
    }
}
