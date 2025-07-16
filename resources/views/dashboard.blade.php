@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <p>📅 You can view and manage your appointments below.</p>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">Book Appointment</a>
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">View My Appointments</a>
</div>
@endsection
