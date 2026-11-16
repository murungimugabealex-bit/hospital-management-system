@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Administrator Dashboard</h1>
        <p class="text-muted mb-0">Hospital overview and quick management</p>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach(['Patients' => $stats['patients'], 'Doctors' => $stats['doctors'], 'Appointments' => $stats['appointments'], 'Departments' => $stats['departments']] as $label => $value)
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">{{ $label }}</div>
                    <div class="display-6 fw-semibold">{{ $value }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card stat-card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Recent appointments</span>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Patient</th><th>Doctor</th><th>Department</th><th>Scheduled</th><th>Status</th></tr>
            </thead>
            <tbody>
            @forelse($recentAppointments as $appt)
                <tr>
                    <td>{{ $appt->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $appt->doctor->name ?? 'N/A' }}</td>
                    <td>{{ $appt->department->name ?? 'N/A' }}</td>
                    <td>{{ $appt->scheduled_at->format('M d, Y H:i') }}</td>
                    <td><span class="badge text-bg-secondary">{{ $appt->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No appointments found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
