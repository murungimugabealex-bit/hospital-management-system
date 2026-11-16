<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_id', 'visit_date',
        'diagnosis', 'symptoms', 'treatment', 'prescriptions', 'notes', 'attachments',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'attachments' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function prescriptionItems()
    {
        return $this->hasMany(Prescription::class);
    }
}
