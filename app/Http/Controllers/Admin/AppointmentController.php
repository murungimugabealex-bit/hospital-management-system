<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;
use App\Models\User;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient.user', 'doctor', 'department'])
            ->latest('scheduled_at')->paginate(20);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('admin.appointments.create', [
            'patients' => Patient::with('user')->get(),
            'doctors' => User::where('role', 'doctor')->where('is_active', true)->get(),
            'departments' => Department::where('is_active', true)->get(),
        ]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        Appointment::create($request->validated());

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function edit(Appointment $appointment)
    {
        return view('admin.appointments.edit', [
            'appointment' => $appointment,
            'patients' => Patient::with('user')->get(),
            'doctors' => User::where('role', 'doctor')->where('is_active', true)->get(),
            'departments' => Department::where('is_active', true)->get(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted successfully.');
    }
}
