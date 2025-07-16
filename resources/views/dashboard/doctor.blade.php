@extends('dashboard.doctor-layout')

@section('doctor-dashboard-content')
<div class="container-fluid mt-4">
    <div class="mb-4">
        <h2>Doctor Dashboard</h2>
        <p>Welcome, Dr. {{ auth()->guard('doctor')->user()->name ?? 'Doctor' }}!</p>
    </div>

    {{-- Stats Section --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Today’s Appointments</h5>
                    <p class="card-text fs-4">{{ $todayAppointmentsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Patients</h5>
                    <p class="card-text fs-4">{{ $totalPatients }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Appointments</h5>
                    <p class="card-text fs-4">{{ $upcomingAppointmentsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Cancelled Appointments</h5>
                    <p class="card-text fs-4">{{ $cancelledAppointmentsCount }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Appointments Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Recent Appointments</span>
            <a href="{{ route('doctor.dashboard.appointments') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body table-responsive">
            @if($recentAppointments->count())
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->full_name }}</td>
                                <td>{{ $appointment->date->format('Y-m-d') }}</td>
                                <td>{{ $appointment->start_time }}</td>
                                <td>{{ $appointment->appointmentType->name }}</td>
                                <td>
                                    @if($appointment->status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @elseif($appointment->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Scheduled</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No recent appointments found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
