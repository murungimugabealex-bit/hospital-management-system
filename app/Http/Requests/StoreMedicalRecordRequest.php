<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'doctor'], true);
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'diagnosis' => 'nullable|string|max:10000',
            'symptoms' => 'nullable|string|max:10000',
            'treatment' => 'nullable|string|max:10000',
            'prescriptions' => 'nullable|string|max:10000',
            'notes' => 'nullable|string|max:10000',
        ];
    }
}
