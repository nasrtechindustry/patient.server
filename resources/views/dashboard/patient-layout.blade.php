@extends('layouts.app')

@section('content')
    <style>
        #sidebarMenu {
            height: 100vh;
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }

        #sidebarMenu .nav-link {
            color: #333;
            font-weight: 500;
            padding: 12px 20px;
            border-left: 4px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        #sidebarMenu .nav-link:hover {
            background-color: #e9ecef;
            color: #007bff;
        }

        #sidebarMenu .nav-link.active {
            background-color: #007bff;
            color: #fff;
            border-left: 4px solid #0056b3;
        }
    </style>

    <div class="container-fluid">
        <div class="row vh-100">
            {{-- Sidebar --}}
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('patient.appointments.create') ? 'active' : '' }}"
                               href="{{ route('patient.appointments.create') }}">
                                <i class="bi bi-plus-square-fill me-2"></i> Book Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('patient.appointments.index') ? 'active' : '' }}"
                               href="{{ route('patient.appointments.index') }}">
                                <i class="bi bi-calendar-check-fill me-2"></i> My Appointments
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            {{-- Main Content --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                {{-- Top Navbar --}}
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
                    <div class="container-fluid">
                        <span class="navbar-text me-auto">
                            Welcome, {{ auth()->guard('patient')->user()->full_name ?? 'Patient' }}
                        </span>
                        <form method="POST" action="{{ route('patient.logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </div>
                </nav>

                {{-- Page Content --}}
                @yield('patient-dashboard-content')
            </main>
        </div>
    </div>
@endsection
