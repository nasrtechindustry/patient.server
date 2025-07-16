@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Doctor Dashboard</h2>
    <p>Welcome, Dr. {{ auth()->guard('doctor')->user()->name ?? 'Doctor' }}!</p>

    {{-- Profile Section --}}
    <div class="card mb-4">
        <div class="card-header">Profile Information</div>
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

    {{-- Working Hours --}}
    <div class="card mb-4">
        <div class="card-header">Working Hours (Editable)</div>
        <div class="card-body">
            <form method="POST" action="{{ route('doctor.working-hours.update') }}">
                @csrf
                @method('PUT')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Weekday</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Appointment Types Allowed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workingHours as $weekday => $hours)
                        <tr>
                            <td>{{ ucfirst($weekday) }}</td>
                            <td><input type="time" name="working_hours[{{ $weekday }}][start]" class="form-control" value="{{ $hours['start'] ?? '' }}"></td>
                            <td><input type="time" name="working_hours[{{ $weekday }}][end]" class="form-control" value="{{ $hours['end'] ?? '' }}"></td>
                            <td>
                                @foreach($appointmentTypes as $type)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" 
                                               name="working_hours[{{ $weekday }}][types][]" 
                                               value="{{ $type->id }}"
                                               {{ in_array($type->id, $hours['types'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $type->name }}</label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Update Working Hours</button>
            </form>
        </div>
    </div>

    {{-- Exception Days --}}
    <div class="card mb-4">
        <div class="card-header">Exception Days (Holidays, Leave)</div>
        <div class="card-body">
            <form method="POST" action="{{ route('doctor.exceptions.add') }}">
                @csrf
                <div class="mb-3">
                    <label for="exception_date" class="form-label">Add Exception Date</label>
                    <input type="date" id="exception_date" name="exception_date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger">Add Exception</button>
            </form>

            @if($exceptions->count() > 0)
                <hr>
                <h5>Existing Exceptions</h5>
                <ul class="list-group">
                    @foreach($exceptions as $exception)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $exception->date->format('Y-m-d') }}
                            <form method="POST" action="{{ route('doctor.exceptions.remove', $exception->id) }}" onsubmit="return confirm('Remove this exception?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Appointments Section --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Appointments</span>
            <form method="GET" action="{{ route('doctor.dashboard') }}" class="d-flex gap-2 align-items-center">
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
        </div>

        <div class="card-body">
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
                                <td>
                                    {{-- Example action buttons (implement logic in controller) --}}
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

                {{-- Pagination --}}
                {{ $appointments->withQueryString()->links() }}

            @else
                <p>No appointments found.</p>
            @endif
        </div>
    </div>

    {{-- Export Schedules --}}
    <div class="card">
        <div class="card-header">Export Schedules</div>
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.export.schedule') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="export_type" class="form-label">Export Type</label>
                    <select id="export_type" name="type" class="form-select" required>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="export_date" class="form-label">Date</label>
                    <input type="date" id="export_date" name="date" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
