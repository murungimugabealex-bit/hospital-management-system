<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'patient_code', 'blood_group', 'allergies', 'chronic_conditions',
        'emergency_contact_name', 'emergency_contact_phone', 'insurance_provider',
        'insurance_number', 'registered_at',
    ];

    protected $casts = ['registered_at' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
