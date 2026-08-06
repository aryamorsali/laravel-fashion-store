<?php

namespace App\Http\Requests\Admin\Notification;

use Illuminate\Foundation\Http\FormRequest;

class EmailFileRequest extends FormRequest
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
                'file' => 'required|mimes:png,jpg,jpeg,gif,pdf,zip,docx,doc|max:2048',
            ];
        } else {
            return [
                'file' => 'mimes:png,jpg,jpeg,gif,pdf,zip,docx,doc|max:2048',
            ];
        }
    }
}
