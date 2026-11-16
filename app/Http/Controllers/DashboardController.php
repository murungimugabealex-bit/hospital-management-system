<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'nurse', 'pharmacist' => redirect()->route('staff.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default => abort(403),
        };
    }
}
