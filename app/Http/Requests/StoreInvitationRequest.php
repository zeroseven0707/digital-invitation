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
        // Normalize time inputs to H:i format
        $this->merge([
            'akad_time_start' => $this->normalizeTime($this->akad_time_start),
            'akad_time_end' => $this->normalizeTime($this->akad_time_end),
            'reception_time_start' => $this->normalizeTime($this->reception_time_start),
            'reception_time_end' => $this->normalizeTime($this->reception_time_end),
        ]);

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
     * Normalize time input to H:i format
     */
    private function normalizeTime(?string $time): ?string
    {
        if ($time === null) {
            return null;
        }

        // If already in H:i format, return as is
        if (preg_match('/^\d{1,2}:\d{2}$/', $time)) {
            return $time;
        }

        // Try to parse and format
        try {
            $dateTime = new \DateTime($time);
            return $dateTime->format('H:i');
        } catch (\Exception $e) {
            return $time; // Return original if parsing fails
        }
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
            'music_file' => ['nullable', 'file', 'mimes:mp3', 'max:10240'], // 10MB max
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
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
            'music_file.file' => 'File musik harus berupa file yang valid.',
            'music_file.mimes' => 'File musik harus berformat MP3.',
            'music_file.max' => 'Ukuran file musik maksimal 10MB.',
        ];
    }
}
