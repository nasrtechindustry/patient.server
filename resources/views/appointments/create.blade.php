@extends('dashboard.patient-layout')

@section('patient-dashboard-content')
<div class="container">
    <h2>Book Appointment</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('patient.appointments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Doctor</label>
            <select name="doctor_id" id="doctor-select" class="form-control @error('doctor_id') is-invalid @enderror" required>
                <option value="">Select Doctor</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>
            @error('doctor_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Appointment Type</label>
            <select name="appointment_type_id" id="type-select" class="form-control @error('appointment_type_id') is-invalid @enderror" required>
                <option value="">Select Type</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('appointment_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('appointment_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" id="date-input" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
            @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Available Slots</label>
            <select name="start_time" id="slot-select" class="form-control @error('start_time') is-invalid @enderror" required>
                <option value="">Select Slot</option>
                {{-- Slots loaded by JS --}}
            </select>
            @error('start_time')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Book Appointment</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const doctorSelect = document.getElementById('doctor-select');
        const typeSelect = document.getElementById('type-select');
        const dateInput = document.getElementById('date-input');
        const slotSelect = document.getElementById('slot-select');

        function fetchSlots() {
            const doctorId = doctorSelect.value;
            const typeId = typeSelect.value;
            const date = dateInput.value;

            if (!doctorId || !typeId || !date) {
                slotSelect.innerHTML = '<option value="">Select Slot</option>';
                return;
            }

            fetch(`/appointments/available-slots?doctor_id=${doctorId}&appointment_type_id=${typeId}&date=${date}`)
                .then(res => res.json())
                .then(response => {
                    slotSelect.innerHTML = '<option value="">Select Slot</option>';

                    if (Array.isArray(response?.original)) {
                        response.original.forEach(slot => {
                            slotSelect.innerHTML += `<option value="${slot.start}">${slot.start} - ${slot.end}</option>`;
                        });
                    } else if (response?.original?.success === false && response?.original?.message) {
                        // Instead of alert, display inline error below slots (optional)
                        slotSelect.innerHTML = `<option value="">${response.original.message}</option>`;
                    }
                })
                .catch(error => {
                    console.error("Error fetching slots:", error);
                    slotSelect.innerHTML = '<option value="">Error loading slots</option>';
                });
        }

        doctorSelect.addEventListener('change', fetchSlots);
        typeSelect.addEventListener('change', fetchSlots);
        dateInput.addEventListener('change', fetchSlots);

    });
</script>
@endsection
