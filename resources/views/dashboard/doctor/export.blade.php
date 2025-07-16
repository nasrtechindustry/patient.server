@extends('dashboard.doctor-layout')

@section('doctor-dashboard-content')
<h2>Export Schedules</h2>

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
@endsection
