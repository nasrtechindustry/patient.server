@extends('dashboard.doctor-layout')

@section('doctor-dashboard-content')
    <div class="container">
        <h2>Reschedule Appointment</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p>
            <strong>Current Date:</strong>
            {{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') ?? $appointment->date }}<br>
            <strong>Current Time:</strong>
            {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') ?? $appointment->start_time }}
        </p>

        <form action="{{ route('doctor.appointments.reschedule', $appointment->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="date">New Date</label>
                <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date', \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') ?? $appointment->date) }}"
                    required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="start_time">New Time</label>
                <select id="start_time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
                    required>
                    <option value="">Select a time slot</option>
                    @foreach($availableSlots as $slot)
                        <option value="{{ $slot['start'] }}" {{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) === $slot['start'] ? 'selected' : '' }}>
                            {{ $slot['start'] }} - {{ $slot['end'] }}
                        </option>
                    @endforeach
                </select>
                @error('start_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Reschedule</button>
        </form>
    </div>
@endsection