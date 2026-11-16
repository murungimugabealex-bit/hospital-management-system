<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recent = Appointment::with(['patient.user', 'doctor', 'department'])->latest()->take(10)->get();

        return view('staff.dashboard', compact('user', 'recent'));
    }
}
