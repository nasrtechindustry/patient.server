<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel Appointment System') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel Appointment System') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-center">

                        {{-- Patient Authenticated --}}
                        @auth('patient')
                            <li class="nav-item me-3">
                                <span class="navbar-text">
                                    {{ auth('patient')->user()->full_name }}
                                </span>
                            </li>
                            <li class="nav-item">
                                <form id="patient-logout-form" action="{{ route('patient.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link nav-link" style="display:inline; padding:0; border:none; cursor:pointer;">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        @endauth

                        {{-- Doctor Authenticated --}}
                        @auth('doctor')
                            <li class="nav-item me-3">
                                <span class="navbar-text">
                                    Dr. {{ auth('doctor')->user()->name }}
                                </span>
                            </li>
                            <li class="nav-item">
                                <form id="doctor-logout-form" action="{{ route('doctor.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link nav-link" style="display:inline; padding:0; border:none; cursor:pointer;">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        @endauth

                        {{-- Neither Patient Nor Doctor Authenticated --}}
                        @guest('patient')
                            @guest('doctor')
                                @if (Route::has('patient.login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('patient.login') }}">Patient Login</a>
                                    </li>
                                @endif
                                @if (Route::has('doctor.login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('doctor.login') }}">Doctor Login</a>
                                    </li>
                                @endif
                            @endguest
                        @endguest

                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS Bundle with Popper (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
