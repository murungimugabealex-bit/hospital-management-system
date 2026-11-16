@extends('layouts.app')

@section('title', 'My Patients')

@section('content')
<h1 class="h3 mb-4">My Patients</h1>
<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Code</th><th>Name</th><th>Phone</th><th>Blood</th><th>Conditions</th><th class="text-end">Action</th></tr></thead>
            <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td><span class="badge text-bg-light">{{ $patient->patient_code }}</span></td>
                    <td>{{ $patient->user->name }}</td>
                    <td>{{ $patient->user->phone ?? '-' }}</td>
                    <td>{{ $patient->blood_group ?? '-' }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $patient->chronic_conditions ?? '-' }}</td>
                    <td class="text-end"><a href="{{ route('doctor.medical-records.create') }}" class="btn btn-sm btn-outline-primary">Add record</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No patients yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $patients->links() }}</div>
</div>
@endsection
