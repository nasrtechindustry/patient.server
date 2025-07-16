@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Book Appointment</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('patient.appointments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Doctor</label>
            <select name="doctor_id" id="doctor-select" class="form-control" required>
                <option value="">Select Doctor</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Appointment Type</label>
            <select name="appointment_type_id" id="type-select" class="form-control" required>
                <option value="">Select Type</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" id="date-input" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Available Slots</label>
            <select name="start_time" id="slot-select" class="form-control" required>
                <option value="">Select Slot</option>
                <!-- Filled dynamically by JS fetching from availableSlots route -->
            </select>
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
            .then(slots => {
                slotSelect.innerHTML = '<option value="">Select Slot</option>';
                slots.forEach(slot => {
                    slotSelect.innerHTML += `<option value="${slot.start}">${slot.start} - ${slot.end}</option>`;
                });
            });
    }

    doctorSelect.addEventListener('change', fetchSlots);
    typeSelect.addEventListener('change', fetchSlots);
    dateInput.addEventListener('change', fetchSlots);
});
</script>
@endsection
