@extends('layouts.app')

@section('title', 'Edit Patient')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit {{ $patient->user->name }}</h1>
    <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<form method="POST" action="{{ route('admin.patients.update', $patient) }}" class="card stat-card">
    @csrf @method('PUT')
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full name</label><input name="name" value="{{ old('name', $patient->user->name) }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $patient->user->email) }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" value="{{ old('phone', $patient->user->phone) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Date of birth</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($patient->user->date_of_birth)->format('Y-m-d')) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select</option>
                    @foreach(['male','female','other'] as $g)<option value="{{ $g }}" @selected(old('gender',$patient->user->gender)===$g)>{{ ucfirst($g) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Blood group</label><input name="blood_group" value="{{ old('blood_group', $patient->blood_group) }}" class="form-control"></div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address', $patient->user->address) }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Allergies</label><textarea name="allergies" class="form-control" rows="2">{{ old('allergies', $patient->allergies) }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Chronic conditions</label><textarea name="chronic_conditions" class="form-control" rows="2">{{ old('chronic_conditions', $patient->chronic_conditions) }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Emergency contact name</label><input name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Emergency contact phone</label><input name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Insurance provider</label><input name="insurance_provider" value="{{ old('insurance_provider', $patient->insurance_provider) }}" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Insurance number</label><input name="insurance_number" value="{{ old('insurance_number', $patient->insurance_number) }}" class="form-control"></div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a href="{{ route('admin.patients.index') }}" class="btn btn-light">Cancel</a>
        <button class="btn btn-primary">Save changes</button>
    </div>
</form>
@endsection
