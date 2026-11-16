@extends('layouts.app')

@section('title', 'New Appointment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Schedule appointment</h1>
    <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<form method="POST" action="{{ route('admin.appointments.store') }}" class="card stat-card">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Patient</label>
                <select name="patient_id" class="form-select" required>
                    <option value="">Select patient</option>
                    @foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id')==$p->id)>{{ $p->user->name }} ({{ $p->patient_code }})</option>@endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Doctor</label>
                <select name="doctor_id" class="form-select" required>
                    <option value="">Select doctor</option>
                    @foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id')==$d->id)>{{ $d->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Department</label>
                <select name="department_id" class="form-select" required>
                    <option value="">Select department</option>
                    @foreach($departments as $dept)<option value="{{ $dept->id }}" @selected(old('department_id')==$dept->id)>{{ $dept->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Scheduled at</label><input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    @foreach(['scheduled','completed','cancelled','no_show'] as $s)<option value="{{ $s }}" @selected(old('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Reason</label><input name="reason" value="{{ old('reason') }}" class="form-control"></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea></div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-light">Cancel</a>
        <button class="btn btn-primary">Create appointment</button>
    </div>
</form>
@endsection
