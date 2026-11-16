@extends('layouts.app')

@section('title', 'New Medical Record')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">New medical record</h1>
    <a href="{{ route('doctor.medical-records.index') }}" class="btn btn-outline-secondary">Back</a>
</div>
<form method="POST" action="{{ route('doctor.medical-records.store') }}" class="card stat-card">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Patient</label>
                <select name="patient_id" class="form-select" required>
                    <option value="">Select patient</option>
                    @foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', optional($selectedAppointment)->patient_id)==$p->id)>{{ $p->user->name }} ({{ $p->patient_code }})</option>@endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Related appointment</label>
                <select name="appointment_id" class="form-select">
                    <option value="">None</option>
                    @foreach($appointments as $a)<option value="{{ $a->id }}" @selected(old('appointment_id', optional($selectedAppointment)->id)==$a->id)>{{ $a->scheduled_at->format('M d, Y H:i') }} - {{ $a->patient->user->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Visit date</label><input type="date" name="visit_date" value="{{ old('visit_date', now()->toDateString()) }}" class="form-control" required></div>
            <div class="col-12"><label class="form-label">Symptoms</label><textarea name="symptoms" class="form-control" rows="2">{{ old('symptoms') }}</textarea></div>
            <div class="col-12"><label class="form-label">Diagnosis</label><textarea name="diagnosis" class="form-control" rows="2">{{ old('diagnosis') }}</textarea></div>
            <div class="col-12"><label class="form-label">Treatment</label><textarea name="treatment" class="form-control" rows="2">{{ old('treatment') }}</textarea></div>
            <div class="col-12"><label class="form-label">Prescriptions</label><textarea name="prescriptions" class="form-control" rows="2">{{ old('prescriptions') }}</textarea></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a href="{{ route('doctor.medical-records.index') }}" class="btn btn-light">Cancel</a>
        <button class="btn btn-primary">Save record</button>
    </div>
</form>
@endsection
