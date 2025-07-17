@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>Patient Registration</h2>

        <form action="{{ route('patient.register') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                    value="{{ old('full_name') }}" required>
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

             <div class="mb-3">
                <label for="phone">Phone Number </label>
                <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>

            <div class="container d-flex justify-content-center mt-4">
                <a href="{{ route('patient.login') }}" class="btn btn-outline-secondary">Have account login</a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Home</a>
            </div>
        </form>
    </div>
@endsection