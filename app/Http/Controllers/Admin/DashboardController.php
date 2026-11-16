<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'patients' => Patient::count(),
                'doctors' => User::where('role', 'doctor')->count(),
                'appointments' => Appointment::count(),
                'departments' => Department::count(),
            ],
            'recentAppointments' => Appointment::with(['patient.user', 'doctor', 'department'])->latest()->take(8)->get(),
        ]);
    }
}
