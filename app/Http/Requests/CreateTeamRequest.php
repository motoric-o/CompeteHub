<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Otorisasi dilakukan di controller/service
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id' => ['required', 'integer', 'exists:competitions,id'],
            'name'           => ['required', 'string', 'min:3', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'competition_id.required' => 'Kompetisi harus dipilih.',
            'competition_id.exists'   => 'Kompetisi tidak ditemukan.',
            'name.required'           => 'Nama tim harus diisi.',
            'name.min'                => 'Nama tim minimal 3 karakter.',
            'name.max'                => 'Nama tim maksimal 150 karakter.',
        ];
    }
}
