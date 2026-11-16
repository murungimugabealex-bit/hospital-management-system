@extends('layouts.app')

@section('title', 'Patient Dashboard')

@section('content')
<h1 class="h3 mb-1">Hello, {{ auth()->user()->name }}</h1>
<p class="text-muted">Patient code: <span class="badge text-bg-light">{{ $patient->patient_code }}</span></p>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card stat-card h-100"><div class="card-body"><div class="text-muted small text-uppercase">Blood group</div><div class="display-6">{{ $patient->blood_group ?? '-' }}</div></div></div></div>
    <div class="col-md-4"><div class="card stat-card h-100"><div class="card-body"><div class="text-muted small text-uppercase">Upcoming appointments</div><div class="display-6">{{ $upcoming->count() }}</div></div></div></div>
    <div class="col-md-4"><div class="card stat-card h-100"><div class="card-body"><div class="text-muted small text-uppercase">Recent records</div><div class="display-6">{{ $records->count() }}</div></div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card stat-card h-100">
            <div class="card-header bg-white fw-semibold">Upcoming appointments</div>
            <ul class="list-group list-group-flush">
                @forelse($upcoming as $appt)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $appt->doctor->name }}</div>
                            <div class="small text-muted">{{ $appt->department->name }} &middot; {{ $appt->scheduled_at->format('M d, Y H:i') }}</div>
                        </div>
                        <span class="badge text-bg-secondary">{{ $appt->status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No upcoming appointments.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card stat-card h-100">
            <div class="card-header bg-white fw-semibold">Recent medical records</div>
            <ul class="list-group list-group-flush">
                @forelse($records as $record)
                    <li class="list-group-item">
                        <div class="fw-semibold">{{ $record->diagnosis ?? 'General visit' }}</div>
                        <div class="small text-muted">{{ $record->visit_date->format('M d, Y') }} &middot; {{ $record->doctor->name ?? 'N/A' }}</div>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No records available.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
