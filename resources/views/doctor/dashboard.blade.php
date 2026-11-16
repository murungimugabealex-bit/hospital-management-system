@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
<h1 class="h3 mb-1">Welcome, {{ auth()->user()->name }}</h1>
<p class="text-muted">{{ auth()->user()->specialization ?? 'Doctor' }} &middot; {{ auth()->user()->department->name ?? 'General' }}</p>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card stat-card h-100"><div class="card-body"><div class="text-muted small text-uppercase">Upcoming</div><div class="display-6">{{ $appointments->where('status','scheduled')->count() }}</div></div></div></div>
    <div class="col-md-4"><div class="card stat-card h-100"><div class="card-body"><div class="text-muted small text-uppercase">Total shown</div><div class="display-6">{{ $appointments->count() }}</div></div></div></div>
    <div class="col-md-4"><div class="card stat-card h-100"><div class="card-body"><div class="text-muted small text-uppercase">Quick action</div><a href="{{ route('doctor.medical-records.create') }}" class="btn btn-primary mt-2"><i class="bi bi-plus-lg me-1"></i>New record</a></div></div></div>
</div>

<div class="card stat-card">
    <div class="card-header bg-white d-flex justify-content-between"><span class="fw-semibold">My appointments</span><a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-outline-primary">View all</a></div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Patient</th><th>Scheduled</th><th>Reason</th><th>Status</th><th class="text-end">Action</th></tr></thead>
            <tbody>
            @forelse($appointments as $appt)
                <tr>
                    <td>{{ $appt->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $appt->scheduled_at->format('M d, Y H:i') }}</td>
                    <td>{{ $appt->reason ?? '-' }}</td>
                    <td><span class="badge text-bg-secondary">{{ $appt->status }}</span></td>
                    <td class="text-end"><a href="{{ route('doctor.medical-records.create', ['appointment_id' => $appt->id]) }}" class="btn btn-sm btn-outline-primary">Add record</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No appointments assigned.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
