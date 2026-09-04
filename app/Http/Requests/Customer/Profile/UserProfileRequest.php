<?php

namespace App\Http\Requests\Customer\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'national_code' => [
                'nullable',
                'string',
                'digits:10',
                Rule::unique('users', 'national_code')->ignore($userId),
            ],
            'profile_photo_path' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', 
            ],
        ];
    }
}
