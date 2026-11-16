<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => ['required', 'exists:users,id', Rule::exists('users', 'id')->where('role', 'doctor')],
            'department_id' => 'required|exists:departments,id',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:scheduled,completed,cancelled,no_show',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:5000',
        ];
    }
}
