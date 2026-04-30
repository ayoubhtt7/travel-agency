<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TravelApp') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="{{ route('trips.index') }}">✈ TravelApp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('trips.*') ? 'active' : '' }}"
                   href="{{ route('trips.index') }}">Trips</a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('flights.*') ? 'active' : '' }}"
                   href="{{ route('flights.index') }}">Flights</a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('flights.status') ? 'active' : '' }}"
                   href="{{ route('flights.status') }}">📡 Live Status</a>
            </li>

            @auth
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}"
                   href="{{ route('bookings.index') }}">My Bookings</a>
            </li>

            @if(auth()->user()->role === 'admin')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-warning {{ request()->is('admin*') ? 'active' : '' }}"
                   href="#" data-bs-toggle="dropdown">Admin</a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">📊 Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.trips.index') }}">🌍 Trips</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.destinations.index') }}">📍 Destinations</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.flights.index') }}">✈ Flights</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.flights.import') }}">🌐 Import Flights</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.bookings.index') }}">🎫 Trip Bookings</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.flight-bookings.index') }}">🎫 Flight Bookings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">👥 Users</a></li>
                </ul>
            </li>
            @endif
            @endauth

        </ul>

        <ul class="navbar-nav ms-auto">
            @guest
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
            @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        👤 {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">🏠 Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">✏️ Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger">🚪 Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @endguest
        </ul>
    </div>
</nav>

<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<main>@yield('content')</main>

<footer class="text-center text-muted py-4 mt-5 border-top">
    <small>&copy; {{ date('Y') }} TravelApp. All rights reserved.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
