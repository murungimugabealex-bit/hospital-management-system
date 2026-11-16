@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Patients</h1>
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New patient</a>
</div>

<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Code</th><th>Name</th><th>Email</th><th>Phone</th><th>Blood</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td><span class="badge text-bg-light">{{ $patient->patient_code }}</span></td>
                    <td>{{ $patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $patient->user->email ?? 'N/A' }}</td>
                    <td>{{ $patient->user->phone ?? '-' }}</td>
                    <td>{{ $patient->blood_group ?? '-' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" class="d-inline" onsubmit="return confirm('Delete this patient?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
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
