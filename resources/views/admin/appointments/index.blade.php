@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Appointments</h1>
    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New appointment</a>
</div>

<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Patient</th><th>Doctor</th><th>Department</th><th>Scheduled</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
            @forelse($appointments as $appt)
                <tr>
                    <td>{{ $appt->patient->user->name ?? 'N/A' }}<div class="small text-muted">{{ $appt->patient->patient_code ?? '' }}</div></td>
                    <td>{{ $appt->doctor->name ?? 'N/A' }}</td>
                    <td>{{ $appt->department->name ?? 'N/A' }}</td>
                    <td>{{ $appt->scheduled_at->format('M d, Y H:i') }}</td>
                    <td><span class="badge text-bg-{{ $appt->status === 'completed' ? 'success' : ($appt->status === 'cancelled' ? 'danger' : 'secondary') }}">{{ $appt->status }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.appointments.edit', $appt) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.appointments.destroy', $appt) }}" class="d-inline" onsubmit="return confirm('Delete this appointment?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No appointments yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $appointments->links() }}</div>
</div>
@endsection
