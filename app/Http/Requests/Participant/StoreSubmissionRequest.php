<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // We will do more detailed authorization in the controller or policy,
        // but basically user must be participant.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'submission_file' => ['required', 'file', 'mimes:pdf,zip,mp4', 'max:20480'], // Max 20MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'submission_file.required' => 'Please upload a submission file.',
            'submission_file.mimes' => 'The file must be a PDF, ZIP, or MP4 format.',
            'submission_file.max' => 'The file size must not exceed 20MB.',
        ];
    }
}
