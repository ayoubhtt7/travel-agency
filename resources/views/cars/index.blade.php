@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-1">🚗 Car Rentals</h2>
    <p class="text-muted mb-4">Rent a car at your destination</p>

    {{-- Filters --}}
    <form method="GET" action="{{ route('cars.index') }}" class="row g-2 mb-4 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Destination</label>
            <select name="destination_id" class="form-select">
                <option value="">All destinations</option>
                @foreach($destinations as $dest)
                    <option value="{{ $dest->id }}" {{ request('destination_id') == $dest->id ? 'selected' : '' }}>
                        {{ $dest->name }}, {{ $dest->country }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Type</label>
            <select name="type" class="form-select">
                <option value="">All types</option>
                @foreach(['economy','compact','suv','luxury','van','convertible'] as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Transmission</label>
            <select name="transmission" class="form-select">
                <option value="">Any</option>
                <option value="automatic" {{ request('transmission') === 'automatic' ? 'selected' : '' }}>Automatic</option>
                <option value="manual"    {{ request('transmission') === 'manual'    ? 'selected' : '' }}>Manual</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Max price / day (DA)</label>
            <input type="number" name="max_price" class="form-control"
                   value="{{ request('max_price') }}" placeholder="e.g. 5000">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
        @if(request()->hasAny(['destination_id','type','transmission','max_price']))
        <div class="col-md-1">
            <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
        </div>
        @endif
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($cars->isEmpty())
        <div class="alert alert-info">No cars available matching your criteria.</div>
    @else
    <div class="row g-4">
        @foreach($cars as $car)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}"
                         class="card-img-top" alt="{{ $car->brand }} {{ $car->model }}"
                         style="height:180px;object-fit:cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center"
                         style="height:180px;font-size:3rem;">🚗</div>
                @endif

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h5 class="card-title mb-0">{{ $car->brand }} {{ $car->model }}</h5>
                        <span class="badge bg-secondary">{{ ucfirst($car->type) }}</span>
                    </div>

                    @if($car->destination)
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>{{ $car->destination->name }}, {{ $car->destination->country }}
                        </p>
                    @endif

                    <div class="row g-1 mb-3 text-center small">
                        <div class="col-4">
                            <div class="bg-light rounded p-1">
                                <div>🪑 {{ $car->seats }} seats</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded p-1">
                                <div>⚙️ {{ ucfirst($car->transmission) }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded p-1">
                                <div>⛽ {{ ucfirst($car->fuel) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-3 flex-wrap">
                        @if($car->with_ac)
                            <span class="badge bg-info text-dark">❄️ AC</span>
                        @endif
                        @if($car->unlimited_mileage)
                            <span class="badge bg-success">∞ Unlimited km</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fs-5 fw-bold text-primary">{{ number_format($car->price_per_day, 2) }} DA</span>
                            <span class="text-muted small"> / day</span>
                        </div>
                        <span class="badge bg-{{ $car->available_units > 2 ? 'success' : 'warning text-dark' }}">
                            {{ $car->available_units }} left
                        </span>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
                    @auth
                    <button class="btn btn-primary w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#bookCar{{ $car->id }}">
                        Rent this car
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login to book</a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Booking Modal --}}
        @auth
        <div class="modal fade" id="bookCar{{ $car->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('cars.book') }}" method="POST">
                        @csrf
                        <input type="hidden" name="car_rental_id" value="{{ $car->id }}">

                        <div class="modal-header">
                            <h5 class="modal-title">Rent — {{ $car->brand }} {{ $car->model }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pick-up date</label>
                                <input type="date" name="pickup_date" class="form-control"
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Return date</label>
                                <input type="date" name="return_date" class="form-control"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pick-up location</label>
                                <input type="text" name="pickup_location" class="form-control"
                                       placeholder="e.g. Airport Terminal 1" required>
                            </div>
                            <div class="alert alert-info small mb-0">
                                <strong>{{ number_format($car->price_per_day, 2) }} DA / day</strong>
                                — total calculated on confirmation.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm Rental</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endauth

        @endforeach
    </div>
    @endif

</div>
@endsection
