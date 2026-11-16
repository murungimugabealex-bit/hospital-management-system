@extends('layouts.app')

@section('title', 'Medical Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Medical Records</h1>
    <a href="{{ route('doctor.medical-records.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New record</a>
</div>
<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Patient</th><th>Visit date</th><th>Diagnosis</th><th class="text-end">Action</th></tr></thead>
            <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ $record->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $record->visit_date->format('M d, Y') }}</td>
                    <td class="text-truncate" style="max-width: 260px;">{{ $record->diagnosis ?? '-' }}</td>
                    <td class="text-end"><a href="{{ route('doctor.medical-records.show', $record) }}" class="btn btn-sm btn-outline-secondary">View</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">No records yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $records->links() }}</div>
</div>
@endsection
