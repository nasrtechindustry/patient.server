@extends('dashboard.doctor-layout')

@section('doctor-dashboard-content')
<h2>Doctor Profile</h2>

<div class="card">
    <div class="card-body">
        <p><strong>Name:</strong> {{ auth()->guard('doctor')->user()->name }}</p>
        <p><strong>Departments:</strong>
            @foreach(auth()->guard('doctor')->user()->departments ?? [] as $department)
                <span class="badge bg-primary">{{ $department->name }}</span>
            @endforeach
        </p>
        <p><strong>Specializations:</strong> {{ auth()->guard('doctor')->user()->specialization ?? '-' }}</p>
    </div>
</div>
@endsection
