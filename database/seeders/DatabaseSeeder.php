<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DepartmentSeeder::class);

        $departments = Department::all()->keyBy('code');

        $admin = User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@hms.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $doctor1 = User::factory()->create([
            'name' => 'Dr. Alice Carter',
            'email' => 'doctor@hms.test',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'department_id' => $departments['CARD']->id ?? null,
            'specialization' => 'Cardiologist',
            'qualification' => 'MD',
            'license_number' => 'LIC-DOC-001',
        ]);

        $doctor2 = User::factory()->create([
            'name' => 'Dr. Brian Lee',
            'email' => 'doctor2@hms.test',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'department_id' => $departments['GEN']->id ?? null,
            'specialization' => 'General Physician',
            'qualification' => 'MBBS',
            'license_number' => 'LIC-DOC-002',
        ]);

        $nurse = User::factory()->create([
            'name' => 'Nina Nurse',
            'email' => 'nurse@hms.test',
            'password' => Hash::make('password'),
            'role' => 'nurse',
            'department_id' => $departments['GEN']->id ?? null,
        ]);

        $pharmacist = User::factory()->create([
            'name' => 'Phil Pharmacist',
            'email' => 'pharmacist@hms.test',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
            'department_id' => $departments['PHARM']->id ?? null,
        ]);

        $patientUsers = User::factory()->count(5)->create([
            'role' => 'patient',
            'password' => Hash::make('password'),
        ]);

        $patients = collect();
        foreach ($patientUsers as $idx => $pu) {
            $patients->push(Patient::create([
                'user_id' => $pu->id,
                'patient_code' => 'PAT-'.str_pad((string) (1000 + $idx), 6, '0', STR_PAD_LEFT),
                'blood_group' => ['A+', 'B+', 'O+', 'AB+', 'A-'][$idx % 5],
                'allergies' => $idx % 2 ? 'Penicillin' : null,
                'chronic_conditions' => $idx % 3 ? 'Hypertension' : null,
                'emergency_contact_name' => 'Emergency Contact '.($idx + 1),
                'emergency_contact_phone' => '555-010'.$idx,
                'insurance_provider' => 'HealthCare Inc',
                'insurance_number' => 'INS-'.(10000 + $idx),
                'registered_at' => now()->subDays(30 - $idx)->toDateString(),
            ]));
        }

        // Also create a known patient login
        $knownPatientUser = User::factory()->create([
            'name' => 'John Patient',
            'email' => 'patient@hms.test',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);
        $knownPatient = Patient::create([
            'user_id' => $knownPatientUser->id,
            'patient_code' => 'PAT-000999',
            'blood_group' => 'O+',
            'allergies' => 'None',
            'chronic_conditions' => 'Diabetes',
            'emergency_contact_name' => 'Jane Patient',
            'emergency_contact_phone' => '555-0199',
            'insurance_provider' => 'MediPlus',
            'insurance_number' => 'INS-99999',
            'registered_at' => now()->subMonths(2)->toDateString(),
        ]);
        $patients->push($knownPatient);

        foreach ($patients as $i => $patient) {
            $doctor = $i % 2 ? $doctor1 : $doctor2;
            $dept = $doctor->department_id ?? $departments['GEN']->id;
            $appt = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $dept,
                'scheduled_at' => now()->addDays($i + 1)->setTime(9 + $i, 0),
                'status' => $i < 3 ? 'completed' : 'scheduled',
                'reason' => 'Routine checkup',
                'notes' => 'Seeded appointment',
            ]);

            $record = MedicalRecord::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'appointment_id' => $appt->id,
                'visit_date' => now()->subDays(7 - $i)->toDateString(),
                'diagnosis' => 'General assessment',
                'symptoms' => 'Mild fatigue',
                'treatment' => 'Rest and hydration',
                'prescriptions' => 'Vitamin D',
                'notes' => 'Follow up in 2 weeks',
            ]);

            Prescription::create([
                'medical_record_id' => $record->id,
                'pharmacist_id' => $pharmacist->id,
                'medication_name' => 'Vitamin D3',
                'dosage' => '1000 IU',
                'frequency' => 'Once daily',
                'duration' => '30 days',
                'status' => $i % 2 ? 'dispensed' : 'pending',
                'instructions' => 'Take after meals',
                'dispensed_at' => $i % 2 ? now() : null,
            ]);
        }
    }
}
