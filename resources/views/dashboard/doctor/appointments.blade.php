@extends('dashboard.doctor-layout')

@section('doctor-dashboard-content')
<h2>Appointments</h2>

<form method="GET" action="{{ route('doctor.dashboard') }}" class="d-flex gap-2 mb-3 align-items-center">
    <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="form-control form-control-sm" placeholder="Filter by Date">
    <select name="filter_department" class="form-select form-select-sm">
        <option value="">All Departments</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ request('filter_department') == $dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
</form>

@if($appointments->count())
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>Appointment Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->patient->full_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}</td>
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
                    <td>
                        @if($appointment->status === 'scheduled')
                            <form method="POST" action="{{ route('doctor.appointments.cancel', $appointment->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">Cancel</button>
                            </form>
                            <a href="{{ route('doctor.appointments.reschedule.form', $appointment->id) }}" class="btn btn-sm btn-secondary">Reschedule</a>
                        @else
                            <em>N/A</em>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $appointments->withQueryString()->links() }}
@else
    <p>No appointments found.</p>
@endif
@endsection
