<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'department_id', 'phone',
        'date_of_birth', 'gender', 'address', 'specialization', 'qualification',
        'license_number', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function medicalRecordsAuthored()
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }

    public function dispensedPrescriptions()
    {
        return $this->hasMany(Prescription::class, 'pharmacist_id');
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;

        return in_array($this->role, $roles, true);
    }
}
