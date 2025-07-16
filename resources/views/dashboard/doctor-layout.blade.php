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
                            <a class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}"
                                href="{{ route('doctor.dashboard') }}">
                                <i class="bi bi-person-fill me-2"></i> Onboarding
                            </a>
                        </li>   
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.dashboard.profile') ? 'active' : '' }}"
                                href="{{ route('doctor.dashboard.profile') }}">
                                <i class="bi bi-person-fill me-2"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.dashboard.working-hours') ? 'active' : '' }}"
                                href="{{ route('doctor.dashboard.working-hours') }}">
                                <i class="bi bi-clock-fill me-2"></i> Working Hours
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.dashboard.exceptions') ? 'active' : '' }}"
                                href="{{ route('doctor.dashboard.exceptions') }}">
                                <i class="bi bi-calendar-x-fill me-2"></i> Exception Days
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.dashboard.appointments') ? 'active' : '' }}"
                                href="{{ route('doctor.dashboard.appointments') }}">
                                <i class="bi bi-calendar-check-fill me-2"></i> Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.dashboard.export') ? 'active' : '' }}"
                                href="{{ route('doctor.dashboard.export') }}">
                                <i class="bi bi-file-earmark-arrow-down-fill me-2"></i> Export Schedules
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            {{-- Main Content --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @yield('doctor-dashboard-content')
            </main>
        </div>
    </div>
@endsection