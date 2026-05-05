<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT SIDE -->
            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- NAV LINKS -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth

                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('trips.index')" :active="request()->routeIs('trips.*')">
                            Trips
                        </x-nav-link>

                        <x-nav-link :href="route('flights.index')" :active="request()->routeIs('flights.*')">
                            Flights
                        </x-nav-link>

                        <x-nav-link :href="route('cars.index')" :active="request()->routeIs('cars.*')">
                            Cars
                        </x-nav-link>

                        <x-nav-link :href="route('hotels.index')" :active="request()->routeIs('hotels.*')">
                            Hotels
                        </x-nav-link>

                        {{-- ADMIN --}}
                        @if(auth()->user()?->isAdmin())
                        <div x-data="{ adminOpen: false }" class="relative flex items-center">

                            <button @click="adminOpen = !adminOpen"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5
                                hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300
                                transition duration-150 ease-in-out gap-1">

                                Admin
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- DROPDOWN -->
                            <div x-show="adminOpen"
                                 @click.outside="adminOpen = false"
                                 x-transition
                                 class="absolute top-full left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg border z-50 py-2">

                                <!-- BOOKINGS -->
                                <div class="px-3 py-1 text-xs text-gray-400 uppercase">Bookings</div>

                                <a href="{{ route('admin.bookings.index') }}" class="dropdown-item">Trip Bookings</a>
                                <a href="{{ route('admin.flight-bookings.index') }}" class="dropdown-item">Flight Bookings</a>
                                <a href="{{ route('admin.car-bookings.index') }}" class="dropdown-item">Car Bookings</a>
                                <a href="{{ route('admin.hotel-bookings.index') }}" class="dropdown-item">Hotel Bookings</a>

                                <hr>

                                <!-- CATALOG -->
                                <div class="px-3 py-1 text-xs text-gray-400 uppercase">Catalogue</div>

                                <a href="{{ route('admin.trips.index') }}" class="dropdown-item">Trips</a>
                                <a href="{{ route('admin.destinations.index') }}" class="dropdown-item">Destinations</a>
                                <a href="{{ route('admin.flights.index') }}" class="dropdown-item">Flights</a>
                                <a href="{{ route('admin.cars.index') }}" class="dropdown-item">Cars</a>
                                <a href="{{ route('admin.hotels.index') }}" class="dropdown-item">Hotels</a>

                                <hr>

                                <!-- SYSTEM -->
                                <div class="px-3 py-1 text-xs text-gray-400 uppercase">System</div>

                                <a href="{{ route('admin.users.index') }}" class="dropdown-item">Users</a>
                            </div>
                        </div>
                        @endif

                    @endauth
                </div>
            </div>

            <!-- RIGHT SIDE -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm rounded-md">
                            {{ Auth::user()->name }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

        </div>
    </div>

</nav>

<style>
.dropdown-item {
    display:block;
    padding:8px 16px;
    font-size:14px;
}
.dropdown-item:hover {
    background:#f3f4f6;
}
</style>