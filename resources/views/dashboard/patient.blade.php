@extends('dashboard.patient-layout')

@section('patient-dashboard-content')
    <h2>Welcome, {{ auth()->guard('patient')->user()->full_name }}!</h2>
    <p>Use the sidebar to book or view appointments.</p>
@endsection
