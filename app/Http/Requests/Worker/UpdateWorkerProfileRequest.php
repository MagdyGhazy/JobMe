<?php

namespace App\Http\Requests\Worker;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|unique:workers,email,'.auth()->guard('worker')->id(),
            'password' => 'nullable|string|min:6',
            'phone' => 'string|max:17',
            'photo' => '',
            'location' => 'string',
        ];
    }
}
