<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patientProfile;
        abort_unless($patient, 403, 'No patient profile.');
        $upcoming = Appointment::with(['doctor', 'department'])
            ->where('patient_id', $patient->id)->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')->take(5)->get();
        $records = MedicalRecord::where('patient_id', $patient->id)->latest('visit_date')->take(5)->get();

        return view('patient.dashboard', compact('patient', 'upcoming', 'records'));
    }

    public function appointments()
    {
        $patient = Auth::user()->patientProfile;
        $appointments = Appointment::with(['doctor', 'department'])
            ->where('patient_id', $patient->id)->latest('scheduled_at')->paginate(15);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function medicalRecords()
    {
        $patient = Auth::user()->patientProfile;
        $records = MedicalRecord::with(['doctor', 'appointment'])
            ->where('patient_id', $patient->id)->latest('visit_date')->paginate(15);

        return view('patient.medical_records.index', compact('records'));
    }
}
