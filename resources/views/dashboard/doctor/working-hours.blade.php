@extends('dashboard.doctor-layout')

@section('doctor-dashboard-content')
<h2>Working Hours (Editable)</h2>

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
@endsection
