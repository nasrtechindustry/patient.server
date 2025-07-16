@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Working Hours</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('doctor.working-hours.update') }}">
        @csrf
        @method('PUT')

        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
            <div class="mb-3">
                <label>{{ ucfirst($day) }} Start</label>
                <input type="time" name="working_hours[{{ $day }}][start]" 
                       value="{{ old('working_hours.' . $day . '.start', $workingHours[$day]['start'] ?? '') }}" />
            </div>

            <div class="mb-3">
                <label>{{ ucfirst($day) }} End</label>
                <input type="time" name="working_hours[{{ $day }}][end]" 
                       value="{{ old('working_hours.' . $day . '.end', $workingHours[$day]['end'] ?? '') }}" />
            </div>

            {{-- Optionally handle appointment types --}}
            {{-- Example:
            <label>Appointment Types</label>
            <select name="working_hours[{{ $day }}][types][]" multiple>
                @foreach($appointmentTypes as $type)
                    <option value="{{ $type->id }}"
                        @if(in_array($type->id, $workingHours[$day]['types'] ?? [])) selected @endif>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            --}}
        @endforeach

        <button type="submit" class="btn btn-primary">Save Working Hours</button>
    </form>
</div>
@endsection
