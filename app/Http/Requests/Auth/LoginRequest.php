<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ucp' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'ucp.required' => 'Silakan isi username Anda.',
            'password.required' => 'Silakan isi kata sandi Anda.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => 'Validation failed',
        ], 422));
    }
}
