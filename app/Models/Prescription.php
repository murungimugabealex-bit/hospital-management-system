<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id', 'pharmacist_id', 'medication_name', 'dosage',
        'frequency', 'duration', 'status', 'instructions', 'dispensed_at',
    ];

    protected $casts = ['dispensed_at' => 'datetime'];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }
}
