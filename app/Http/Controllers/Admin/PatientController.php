<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->latest()->paginate(15);

        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'patient',
                'phone' => $data['phone'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            Patient::create([
                'user_id' => $user->id,
                'patient_code' => 'PAT-'.str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
                'blood_group' => $data['blood_group'] ?? null,
                'allergies' => $data['allergies'] ?? null,
                'chronic_conditions' => $data['chronic_conditions'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'insurance_provider' => $data['insurance_provider'] ?? null,
                'insurance_number' => $data['insurance_number'] ?? null,
                'registered_at' => now()->toDateString(),
            ]);
        });

        return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully.');
    }

    public function edit(Patient $patient)
    {
        $patient->load('user');

        return view('admin.patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $patient) {
            $patient->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            $patient->update([
                'blood_group' => $data['blood_group'] ?? null,
                'allergies' => $data['allergies'] ?? null,
                'chronic_conditions' => $data['chronic_conditions'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'insurance_provider' => $data['insurance_provider'] ?? null,
                'insurance_number' => $data['insurance_number'] ?? null,
            ]);
        });

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->user->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted successfully.');
    }
}
