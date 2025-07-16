@extends('dashboard.doctor-layout')


@section('doctor-dashboard-content')
    <div class="container mt-4">
        <h2>Doctor Dashboard</h2>
        <p>Welcome, Dr. {{ auth()->guard('doctor')->user()->name ?? 'Doctor' }}!</p>
    </div>
@endsection