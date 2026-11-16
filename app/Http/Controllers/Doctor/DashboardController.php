<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = Auth::user();
        $appointments = Appointment::with(['patient.user', 'department'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('scheduled_at')->take(10)->get();

        return view('doctor.dashboard', compact('appointments'));
    }

    public function appointments()
    {
        $appointments = Appointment::with(['patient.user', 'department'])
            ->where('doctor_id', Auth::id())->latest('scheduled_at')->paginate(20);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function patients()
    {
        $patientIds = Appointment::where('doctor_id', Auth::id())->pluck('patient_id')->unique();
        $records = Patient::with('user')->whereIn('id', $patientIds)->paginate(20);

        return view('doctor.patients.index', ['patients' => $records]);
    }
}
