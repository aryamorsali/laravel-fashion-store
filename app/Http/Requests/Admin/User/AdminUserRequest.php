<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserRequest extends FormRequest
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
                'first_name' => 'required|max:120|min:1|regex:/^[ا-یa-zA-Zء-ي ]+$/u',
                'last_name' => 'required|max:120|min:1|regex:/^[ا-یa-zA-Zء-ي ]+$/u',
                'mobile' => 'required|digits:11|unique:users',
                'email' => 'required|string|email|unique:users',
                'password' => ['required', Password::min(6)->letters()->mixedCase()->symbols()->numbers()->uncompromised(), 'confirmed'],
                'profile_photo_path' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
                'activation' => 'required|numeric|in:0,1',
            ];
        } else {
            return [
                'first_name' => 'required|max:120|min:1|regex:/^[ا-یa-zA-Zء-ي ]+$/u',
                'last_name' => 'required|max:120|min:1|regex:/^[ا-یa-zA-Zء-ي ]+$/u',
                'mobile' => ['required', 'digits:11', Rule::unique('users')->ignore($this->admin)],
                'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($this->admin)],
                'password' => ['nullable', Password::min(6)->letters()->mixedCase()->symbols()->numbers()->uncompromised(), 'confirmed'],
                'profile_photo_path' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
                'activation' => 'required|numeric|in:0,1',
            ];
        }
    }
}
