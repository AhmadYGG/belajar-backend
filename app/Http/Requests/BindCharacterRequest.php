<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BindCharacterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'character_id' => 'required|integer|exists:characters,ID',
        ];
    }

    public function messages(): array
    {
        return [
            'character_id.required' => 'Karakter harus dipilih.',
            'character_id.exists' => 'Karakter tidak ditemukan.',
        ];
    }
}
