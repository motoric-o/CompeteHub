<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invite_code' => ['required', 'string', 'size:8', 'alpha_num'],
        ];
    }

    public function messages(): array
    {
        return [
            'invite_code.required'  => 'Kode undangan harus diisi.',
            'invite_code.size'      => 'Kode undangan harus 8 karakter.',
            'invite_code.alpha_num' => 'Kode undangan hanya boleh berisi huruf dan angka.',
        ];
    }
}
