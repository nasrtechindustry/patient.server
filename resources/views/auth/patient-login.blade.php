@extends('layouts.app')

@section('content')
    <div class="container mt-5" style="max-width: 400px">
        <h4 class="mb-4">Patient Login</h4>
        <form method="POST" action="{{ route('patient.login') }}">
            @csrf

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-success w-100">Login</button>

            <div class="container d-flex justify-content-center mt-4">
                <a href="{{ route('patient.register.form') }}" class="btn btn-outline-secondary">No account register</a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Home</a>
            </div>
        </form>
    </div>
@endsection