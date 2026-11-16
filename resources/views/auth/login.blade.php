@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-sm border-0" style="max-width: 420px; width: 100%;">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-hospital text-primary" style="font-size: 2.5rem;"></i>
                <h1 class="h4 mt-2 mb-1">{{ config('app.name', 'HMS') }}</h1>
                <p class="text-muted small">Hospital Management System</p>
            </div>
            @if($errors->any())
                <div class="alert alert-danger small">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </form>
            <div class="mt-4 small text-muted">
                <strong>Demo accounts</strong> (password: <code>password</code>):<br>
                admin@hms.test &middot; doctor@hms.test &middot; nurse@hms.test &middot; pharmacist@hms.test &middot; patient@hms.test
            </div>
        </div>
    </div>
</div>
@endsection
