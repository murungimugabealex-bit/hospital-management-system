@extends('layouts.app')

@section('title', 'My Appointments')

@section('content')
<h1 class="h3 mb-4">My Appointments</h1>
<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Patient</th><th>Department</th><th>Scheduled</th><th>Reason</th><th>Status</th><th class="text-end">Action</th></tr></thead>
            <tbody>
            @forelse($appointments as $appt)
                <tr>
                    <td>{{ $appt->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $appt->department->name ?? '-' }}</td>
                    <td>{{ $appt->scheduled_at->format('M d, Y H:i') }}</td>
                    <td>{{ $appt->reason ?? '-' }}</td>
                    <td><span class="badge text-bg-secondary">{{ $appt->status }}</span></td>
                    <td class="text-end"><a href="{{ route('doctor.medical-records.create', ['appointment_id' => $appt->id]) }}" class="btn btn-sm btn-outline-primary">Record</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No appointments.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $appointments->links() }}</div>
</div>
@endsection
