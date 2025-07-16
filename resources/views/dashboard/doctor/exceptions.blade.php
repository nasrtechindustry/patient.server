@extends('dashboard.doctor-layout')

@section('doctor-dashboard-content')
<h2>Exception Days (Holidays, Leave)</h2>

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
                {{ \Carbon\Carbon::parse($exception->date)->format('Y-m-d') }}
                <form method="POST" action="{{ route('doctor.exceptions.remove', $exception->id) }}" onsubmit="return confirm('Remove this exception?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Remove</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif
@endsection
