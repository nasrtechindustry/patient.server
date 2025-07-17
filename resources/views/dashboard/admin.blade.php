@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Welcome Admin</h2>
    <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection
