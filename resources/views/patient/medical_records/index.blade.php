@extends('layouts.app')

@section('title', 'My Medical Records')

@section('content')
<h1 class="h3 mb-4">My Medical Records</h1>
<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Visit date</th><th>Doctor</th><th>Diagnosis</th><th>Treatment</th></tr></thead>
            <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ $record->visit_date->format('M d, Y') }}</td>
                    <td>{{ $record->doctor->name ?? 'N/A' }}</td>
                    <td>{{ $record->diagnosis ?? '-' }}</td>
                    <td>{{ $record->treatment ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">No records.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $records->links() }}</div>
</div>
@endsection
