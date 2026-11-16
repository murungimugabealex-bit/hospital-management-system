<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'HMS') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #0d1b2a; }
        .sidebar .nav-link { color: #c8d6e5; border-radius: .5rem; margin: .15rem .5rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #1b263b; color: #fff; }
        .sidebar .brand { color: #fff; font-weight: 700; letter-spacing: .5px; }
        .stat-card { border: 0; border-radius: 1rem; box-shadow: 0 4px 14px rgba(0,0,0,.06); }
        .badge-role { text-transform: capitalize; }
        @media (max-width: 767.98px) {
            .sidebar { min-height: auto; }
        }
    </style>
    @stack('styles')
</head>
<body>
@auth
<nav class="navbar navbar-expand-lg navbar-dark bg-dark d-lg-none">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name','HMS') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="offcanvas-lg offcanvas-start sidebar col-lg-2 p-0" tabindex="-1" id="sidebarMenu">
            <div class="offcanvas-header border-bottom border-secondary">
                <span class="brand offcanvas-title">HMS</span>
                <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column p-0">
                <div class="p-3 text-white">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <span class="badge text-bg-info badge-role">{{ auth()->user()->role }}</span>
                </div>
                <ul class="nav flex-column mb-auto">
                    @php($role = auth()->user()->role)
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active':'' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    @if($role === 'admin')
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.patients.*') ? 'active':'' }}" href="{{ route('admin.patients.index') }}"><i class="bi bi-people me-2"></i>Patients</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active':'' }}" href="{{ route('admin.appointments.index') }}"><i class="bi bi-calendar2-check me-2"></i>Appointments</a></li>
                    @endif
                    @if($role === 'doctor')
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('doctor.appointments.*') ? 'active':'' }}" href="{{ route('doctor.appointments.index') }}"><i class="bi bi-calendar2-check me-2"></i>My Appointments</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('doctor.patients.*') ? 'active':'' }}" href="{{ route('doctor.patients.index') }}"><i class="bi bi-people me-2"></i>My Patients</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('doctor.medical-records.*') ? 'active':'' }}" href="{{ route('doctor.medical-records.index') }}"><i class="bi bi-clipboard2-pulse me-2"></i>Medical Records</a></li>
                    @endif
                    @if($role === 'patient')
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('patient.appointments.*') ? 'active':'' }}" href="{{ route('patient.appointments.index') }}"><i class="bi bi-calendar2-check me-2"></i>My Appointments</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('patient.medical-records.*') ? 'active':'' }}" href="{{ route('patient.medical-records.index') }}"><i class="bi bi-folder2-open me-2"></i>My Records</a></li>
                    @endif
                    @if(in_array($role, ['nurse','pharmacist']))
                        <li class="nav-item"><a class="nav-link active" href="{{ route('staff.dashboard') }}"><i class="bi bi-grid me-2"></i>Staff Console</a></li>
                    @endif
                </ul>
                <form method="POST" action="{{ route('logout') }}" class="p-3 border-top border-secondary">
                    @csrf
                    <button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
                </form>
            </div>
        </div>
        <main class="col-lg-10 py-4 px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@else
    @yield('content')
@endauth
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
