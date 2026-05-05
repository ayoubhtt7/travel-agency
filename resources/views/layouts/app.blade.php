<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TravelApp') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        html,body{height:100%;margin:0}
        body{display:flex;flex-direction:column;background:#f5f6fa}

        .app-navbar{position:sticky;top:0;z-index:1030;background:#0f172a;height:56px;box-shadow:0 2px 8px rgba(0,0,0,.35)}
        .app-navbar .navbar-brand{font-weight:700;letter-spacing:.5px;color:#38bdf8!important}

        #appSidebar{position:fixed;top:56px;left:0;bottom:0;width:240px;background:#1e293b;color:#cbd5e1;display:flex;flex-direction:column;transform:translateX(-100%);transition:transform .28s ease;z-index:1020;overflow-y:auto}
        #appSidebar.open{transform:translateX(0)}

        .sidebar-header{padding:1.25rem 1.25rem .75rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:#64748b;border-bottom:1px solid #334155}
        .sidebar-nav{padding:.5rem 0;flex:1}
        .sidebar-nav .nav-link{display:flex;align-items:center;gap:.65rem;padding:.6rem 1.25rem;color:#94a3b8;font-size:.875rem;transition:background .15s,color .15s}
        .sidebar-nav .nav-link:hover,.sidebar-nav .nav-link.active{background:#334155;color:#f1f5f9}
        .sidebar-nav .nav-link i{font-size:1rem;width:1.2rem;text-align:center}

        .sidebar-section-label{padding:.75rem 1.25rem .25rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#475569}

        .sidebar-user{padding:1rem 1.25rem;border-top:1px solid #334155;font-size:.8rem;color:#64748b;display:flex;align-items:center;gap:.6rem}
        .sidebar-user .avatar{width:30px;height:30px;border-radius:50%;background:#38bdf8;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;color:#0f172a;flex-shrink:0}

        #sidebarOverlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1015}
        #sidebarOverlay.show{display:block}

        .app-wrapper{display:flex;flex:1;flex-direction:column;transition:margin-left .28s ease}
        .app-main{flex:1;padding:1.5rem}
        .app-footer{text-align:center;padding:1rem;font-size:.8rem;color:#94a3b8;border-top:1px solid #e2e8f0;background:#fff}

        @media(min-width:992px){
            #appSidebar{transform:translateX(0)!important}
            .app-wrapper{margin-left:240px}
            #sidebarOverlay{display:none!important}
        }
    </style>

    @stack('styles')
</head>

<body>

{{-- NAVBAR --}}
<nav class="app-navbar navbar navbar-dark px-3">
    <button class="btn btn-link text-light sidebar-toggle p-1 me-2" id="sidebarToggleBtn">
        <i class="bi bi-list fs-4"></i>
    </button>

    <a class="navbar-brand" href="{{ route('trips.index') }}">✈ TravelApp</a>

    <div class="ms-auto d-flex align-items-center gap-2">
        @guest
            <a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">Login</a>
            <a class="btn btn-sm btn-info text-dark fw-semibold" href="{{ route('register') }}">Register</a>
        @else
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary text-light dropdown-toggle d-flex align-items-center gap-1" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                    @if(auth()->user()->role === 'admin')
                    <li><a class="dropdown-item text-warning" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endguest
    </div>
</nav>

{{-- OVERLAY --}}
<div id="sidebarOverlay"></div>

{{-- SIDEBAR --}}
<aside id="appSidebar">
    <div class="sidebar-header">Navigation</div>
    <nav class="sidebar-nav">
        
        <a class="nav-link {{ request()->routeIs('flights.*') ? 'active' : '' }}" href="{{ route('flights.index') }}">
            <i class="bi bi-airplane"></i> flights
        </a>

        <a class="nav-link {{ request()->routeIs('trips.*') ? 'active' : '' }}" href="{{ route('trips.index') }}">
            <i class="bi bi-compass"></i> Explore Trips
        </a>

        {{-- Cars --}}
        <a class="nav-link {{ request()->routeIs('cars.*') ? 'active' : '' }}" href="{{ route('cars.index') }}">
            <i class="bi bi-car-front-fill"></i> Cars
        </a>

        {{-- Hotels --}}
        <a class="nav-link {{ request()->routeIs('hotels.*') ? 'active' : '' }}" href="{{ route('hotels.index') }}">
            <i class="bi bi-building"></i> Hotels
        </a>

        @auth
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
            <i class="bi bi-calendar-check"></i> My Bookings
        </a>

        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
            <i class="bi bi-person-badge"></i> My Profile
        </a>

        @if(auth()->user()->role === 'admin')
            <div class="sidebar-section-label">Admin</div>

            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Admin Dashboard
            </a>

            <a class="nav-link {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}" href="{{ route('admin.trips.index') }}">
                <i class="bi bi-map"></i> Manage Trips
            </a>

            <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                <i class="bi bi-journal-text"></i> Manage Bookings
            </a>

            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="bi bi-people"></i> Manage Users
            </a>

            <a class="nav-link {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}" href="{{ route('admin.destinations.index') }}">
                <i class="bi bi-geo-alt"></i> Destinations
            </a>

            <a class="nav-link {{ request()->routeIs('admin.flights.*') ? 'active' : '' }}" href="{{ route('admin.flights.index') }}">
                <i class="bi bi-airplane"></i> Flights
            </a>

            <a class="nav-link {{ request()->routeIs('admin.flight-bookings.*') ? 'active' : '' }}" href="{{ route('admin.flight-bookings.index') }}">
                <i class="bi bi-journal-check"></i> Flight Bookings
            </a>

            {{-- Cars --}}
            <a class="nav-link {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}" href="{{ route('admin.cars.index') }}">
                <i class="bi bi-car-front-fill"></i> Cars
            </a>

            <a class="nav-link {{ request()->routeIs('admin.car-bookings.*') ? 'active' : '' }}" href="{{ route('admin.car-bookings.index') }}">
                <i class="bi bi-journal-check"></i> Car Bookings
            </a>

            {{-- Hotels --}}
            <a class="nav-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}" href="{{ route('admin.hotels.index') }}">
                <i class="bi bi-building"></i> Hotels
            </a>

            <a class="nav-link {{ request()->routeIs('admin.hotel-bookings.*') ? 'active' : '' }}" href="{{ route('admin.hotel-bookings.index') }}">
                <i class="bi bi-journal-medical"></i> Hotel Bookings
            </a>
        @endif
        @endauth

        @guest
            <a class="nav-link" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>

            <a class="nav-link" href="{{ route('register') }}">
                <i class="bi bi-person-plus"></i> Register
            </a>
        @endguest

    </nav>

    @auth
    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div>
            <div class="fw-semibold text-light">{{ auth()->user()->name }}</div>
            <div>{{ auth()->user()->email }}</div>
        </div>
    </div>
    @endauth
</aside>

{{-- MAIN --}}
<div class="app-wrapper">
    <main class="app-main">
        @yield('content')
    </main>

    <footer class="app-footer">
        &copy; {{ date('Y') }} TravelApp
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const sidebar = document.getElementById('appSidebar');
const overlay = document.getElementById('sidebarOverlay');

document.getElementById('sidebarToggleBtn').addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
});
</script>

@stack('scripts')
</body>
</html>