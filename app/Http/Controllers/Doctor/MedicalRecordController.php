<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = MedicalRecord::with(['patient.user', 'appointment'])
            ->where('doctor_id', Auth::id())->latest('visit_date')->paginate(20);

        return view('doctor.medical_records.index', compact('records'));
    }

    public function create(Request $request)
    {
        $patients = Patient::with('user')->get();
        $appointments = Appointment::where('doctor_id', Auth::id())->with('patient.user')->get();
        $selectedAppointment = $request->filled('appointment_id')
            ? Appointment::where('doctor_id', Auth::id())->find($request->integer('appointment_id'))
            : null;

        return view('doctor.medical_records.create', compact('patients', 'appointments', 'selectedAppointment'));
    }

    public function store(StoreMedicalRecordRequest $request)
    {
        $data = $request->validated();
        $data['doctor_id'] = Auth::id();
        $record = MedicalRecord::create($data);

        if (! empty($data['appointment_id'])) {
            Appointment::where('id', $data['appointment_id'])->update(['status' => 'completed']);
        }

        return redirect()->route('doctor.medical-records.show', $record)
            ->with('success', 'Medical record created successfully.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        abort_unless($medicalRecord->doctor_id === Auth::id() || Auth::user()->role === 'admin', 403);
        $medicalRecord->load(['patient.user', 'appointment', 'prescriptionItems']);

        return view('doctor.medical_records.show', compact('medicalRecord'));
    }
}
