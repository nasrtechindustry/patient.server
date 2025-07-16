@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Patient Dashboard</h2>
    <p>Welcome, {{ auth()->guard('patient')->user()->full_name ?? 'Patient' }}!</p>

    <div class="mt-4">
        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
            Book New Appointment
        </a>

        <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary ms-2">
            View My Appointments
        </a>
    </div>
</div>
@endsection
