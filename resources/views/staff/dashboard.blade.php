@extends('layouts.app')

@section('title', 'Staff Console')

@section('content')
<h1 class="h3 mb-1">Staff Console</h1>
<p class="text-muted">Signed in as <span class="badge text-bg-info">{{ $user->role }}</span></p>
<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Recent appointments</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Patient</th><th>Doctor</th><th>Department</th><th>Scheduled</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($recent as $appt)
                <tr>
                    <td>{{ $appt->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $appt->doctor->name ?? 'N/A' }}</td>
                    <td>{{ $appt->department->name ?? '-' }}</td>
                    <td>{{ $appt->scheduled_at->format('M d, Y H:i') }}</td>
                    <td><span class="badge text-bg-secondary">{{ $appt->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
