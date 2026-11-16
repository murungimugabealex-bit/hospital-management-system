@extends('layouts.app')

@section('title', 'Medical Record')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Medical record</h1>
    <a href="{{ route('doctor.medical-records.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<div class="card stat-card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between flex-wrap gap-2">
            <div><div class="text-muted small">Patient</div><div class="fw-semibold">{{ $medicalRecord->patient->user->name ?? 'N/A' }}</div></div>
            <div><div class="text-muted small">Visit date</div><div class="fw-semibold">{{ $medicalRecord->visit_date->format('M d, Y') }}</div></div>
            <div><div class="text-muted small">Doctor</div><div class="fw-semibold">{{ $medicalRecord->doctor->name ?? 'N/A' }}</div></div>
        </div>
        <hr>
        @foreach(['symptoms' => 'Symptoms', 'diagnosis' => 'Diagnosis', 'treatment' => 'Treatment', 'prescriptions' => 'Prescriptions', 'notes' => 'Notes'] as $field => $label)
            <div class="mb-3">
                <div class="text-muted small text-uppercase">{{ $label }}</div>
                <div>{{ $medicalRecord->$field ?: '-' }}</div>
            </div>
        @endforeach
    </div>
</div>
@if($medicalRecord->prescriptionItems->count())
<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Prescriptions</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Medication</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Status</th></tr></thead>
            <tbody>
            @foreach($medicalRecord->prescriptionItems as $rx)
                <tr><td>{{ $rx->medication_name }}</td><td>{{ $rx->dosage }}</td><td>{{ $rx->frequency }}</td><td>{{ $rx->duration }}</td><td><span class="badge text-bg-secondary">{{ $rx->status }}</span></td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
