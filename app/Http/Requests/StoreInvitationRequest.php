<?php

namespace App\Http\Requests;

use App\Rules\SanitizeHtml;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize text inputs
        $this->merge([
            'bride_name' => $this->sanitizeInput($this->bride_name),
            'bride_father_name' => $this->sanitizeInput($this->bride_father_name),
            'bride_mother_name' => $this->sanitizeInput($this->bride_mother_name),
            'groom_name' => $this->sanitizeInput($this->groom_name),
            'groom_father_name' => $this->sanitizeInput($this->groom_father_name),
            'groom_mother_name' => $this->sanitizeInput($this->groom_mother_name),
            'akad_location' => $this->sanitizeInput($this->akad_location),
            'reception_location' => $this->sanitizeInput($this->reception_location),
            'full_address' => $this->sanitizeInput($this->full_address),
        ]);
    }

    /**
     * Sanitize input string
     */
    private function sanitizeInput(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        return SanitizeHtml::sanitize($input);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'template_id' => ['required', 'integer', 'exists:templates,id'],
            'bride_name' => ['required', 'string', 'max:255', new SanitizeHtml()],
            'bride_father_name' => ['nullable', 'string', 'max:255', new SanitizeHtml()],
            'bride_mother_name' => ['nullable', 'string', 'max:255', new SanitizeHtml()],
            'groom_name' => ['required', 'string', 'max:255', new SanitizeHtml()],
            'groom_father_name' => ['nullable', 'string', 'max:255', new SanitizeHtml()],
            'groom_mother_name' => ['nullable', 'string', 'max:255', new SanitizeHtml()],
            'akad_date' => ['required', 'date', 'after_or_equal:today'],
            'akad_time_start' => ['required', 'date_format:H:i'],
            'akad_time_end' => ['required', 'date_format:H:i', 'after:akad_time_start'],
            'akad_location' => ['required', 'string', 'max:500', new SanitizeHtml()],
            'reception_date' => ['required', 'date', 'after_or_equal:akad_date'],
            'reception_time_start' => ['required', 'date_format:H:i'],
            'reception_time_end' => ['required', 'date_format:H:i', 'after:reception_time_start'],
            'reception_location' => ['required', 'string', 'max:500', new SanitizeHtml()],
            'full_address' => ['required', 'string', 'max:1000', new SanitizeHtml()],
            'google_maps_url' => ['nullable', 'url', 'max:1000'],
            'music_url' => ['nullable', 'url', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'template_id.required' => 'Silakan pilih template undangan.',
            'template_id.exists' => 'Template yang dipilih tidak valid.',
            'bride_name.required' => 'Nama mempelai wanita wajib diisi.',
            'groom_name.required' => 'Nama mempelai pria wajib diisi.',
            'akad_date.required' => 'Tanggal akad wajib diisi.',
            'akad_date.after_or_equal' => 'Tanggal akad tidak boleh di masa lalu.',
            'akad_time_start.required' => 'Waktu mulai akad wajib diisi.',
            'akad_time_end.required' => 'Waktu selesai akad wajib diisi.',
            'akad_time_end.after' => 'Waktu selesai akad harus setelah waktu mulai.',
            'akad_location.required' => 'Lokasi akad wajib diisi.',
            'reception_date.required' => 'Tanggal resepsi wajib diisi.',
            'reception_date.after_or_equal' => 'Tanggal resepsi tidak boleh sebelum tanggal akad.',
            'reception_time_start.required' => 'Waktu mulai resepsi wajib diisi.',
            'reception_time_end.required' => 'Waktu selesai resepsi wajib diisi.',
            'reception_time_end.after' => 'Waktu selesai resepsi harus setelah waktu mulai.',
            'reception_location.required' => 'Lokasi resepsi wajib diisi.',
            'full_address.required' => 'Alamat lengkap wajib diisi.',
            'google_maps_url.url' => 'URL Google Maps tidak valid.',
            'music_url.url' => 'URL musik tidak valid.',
        ];
    }
}
