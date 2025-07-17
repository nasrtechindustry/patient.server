@extends('dashboard.patient-layout')


@section('patient-dashboard-content')
<div class="container">
    <h2>Your Appointments</h2>

    @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Type</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($appointments as $appt)
            <tr>
                <td>{{ $appt->doctor->name }}</td>
                <td>{{ $appt->appointmentType->name }}</td>
                <td>{{ $appt->date }}</td>
                <td>{{ $appt->start_time }}</td>
                <td>{{ $appt->end_time }}</td>
                <td>{{ ucfirst($appt->status) }}</td>
                <td>
                    @if($appt->status === 'booked')
                        <!-- Simple Cancel Form -->
                        <form method="POST" action="{{ route('patient.appointments.cancel', $appt->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                        </form>

                        <!-- Reschedule link or modal trigger could go here -->
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No appointments found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
