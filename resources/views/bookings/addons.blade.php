@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:900px;">

    {{-- Success banner --}}
    <div class="alert alert-success d-flex align-items-center gap-3 mb-4">
        <span style="font-size:2rem;">🎉</span>
        <div>
            <strong>Trip booked!</strong> Your booking for
            <strong>{{ $booking->trip->title }}</strong> is confirmed.
            <div class="small text-muted mt-1">
                Would you like to add a hotel or rent a car at your destination?
            </div>
        </div>
    </div>

    {{-- Trip summary strip --}}
    <div class="card border-0 bg-light mb-4">
        <div class="card-body py-2 px-3 d-flex flex-wrap gap-3 align-items-center small">
            <span>📅 {{ $booking->trip->start_date->format('d M Y') }} → {{ $booking->trip->end_date->format('d M Y') }}</span>
            <span>📍 {{ $booking->trip->destination->name ?? 'N/A' }}</span>
            <span>👥 {{ $booking->number_of_persons }} person{{ $booking->number_of_persons > 1 ? 's' : '' }}</span>
            <span class="ms-auto">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    Skip — go to dashboard
                </a>
            </span>
        </div>
    </div>

    {{-- ===== HOTELS ===== --}}
   <h4 class="mb-3">🏨 Hotels at {{ $booking->trip->destination->name ?? 'your destination' }}</h4>

@if($hotels->isEmpty())
    <div class="alert alert-light border mb-4">
        No hotels available at this destination yet.
    </div>
@else

<div class="row g-3 mb-5">

@foreach($hotels as $hotel)
<div class="col-md-6">

    <div class="card border-0 shadow-sm h-100">

        <div class="row g-0">

            {{-- IMAGE --}}
            <div class="col-4">
                @if($hotel->image)
                    <img src="{{ asset('storage/' . $hotel->image) }}"
                         class="img-fluid rounded-start h-100"
                         style="object-fit:cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center h-100"
                         style="font-size:2rem;">🏨</div>
                @endif
            </div>

            {{-- HOTEL INFO --}}
            <div class="col-8">
                <div class="card-body py-2 px-3">

                    <div class="text-warning small">
                        {{ str_repeat('★', $hotel->stars) }}{{ str_repeat('☆', 5 - $hotel->stars) }}
                    </div>

                    <h6 class="mb-1">{{ $hotel->name }}</h6>

                    @php $minPrice = $hotel->rooms->min('price_per_night'); @endphp
                    @if($minPrice)
                        <div class="small mb-2">
                            From <strong class="text-primary">{{ number_format($minPrice, 2) }} DA</strong>/night
                        </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- ✅ ROOMS DIRECTLY VISIBLE --}}
        <div class="card-body border-top bg-white">

            @foreach($hotel->rooms->where('available_rooms', '>', 0) as $room)

            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                <div>
                    <div class="fw-semibold small">
                        {{ ucfirst($room->type) }} · {{ $room->capacity }} guests
                    </div>

                    <div class="text-muted" style="font-size:0.75rem;">
                        {{ $room->with_breakfast ? '🍳 Breakfast · ' : '' }}
                        {{ $room->refundable ? '✓ Refundable' : 'Non-refundable' }}
                    </div>
                </div>

                <div class="text-end">
                    <div class="small fw-bold text-primary">
                        {{ number_format($room->price_per_night, 2) }} DA/night
                    </div>

                    <button class="btn btn-sm btn-outline-primary mt-1"
                            data-bs-toggle="modal"
                            data-bs-target="#addonRoom{{ $room->id }}">
                        Book
                    </button>
                </div>

            </div>

            {{-- ROOM MODAL --}}
            <div class="modal fade" id="addonRoom{{ $room->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <form action="{{ route('hotels.book') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hotel_room_id" value="{{ $room->id }}">
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    {{ ucfirst($room->type) }} Room — {{ $hotel->name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row g-3">

                                    <div class="col-6">
                                        <label class="form-label">Check-in</label>
                                        <input type="date" name="check_in" class="form-control"
                                               value="{{ $booking->trip->start_date->format('Y-m-d') }}"
                                               required>
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Check-out</label>
                                        <input type="date" name="check_out" class="form-control"
                                               value="{{ $booking->trip->end_date->format('Y-m-d') }}"
                                               required>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Guests</label>
                                        <input type="number" name="guests" class="form-control"
                                               value="{{ $booking->number_of_persons }}"
                                               min="1" max="{{ $room->capacity }}" required>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Add to my trip
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            @endforeach

        </div>

    </div>

</div>
@endforeach

</div>
@endif

    {{-- ===== CARS ===== --}}
    <h4 class="mb-3">🚗 Car Rentals at {{ $booking->trip->destination->name ?? 'your destination' }}</h4>

    @if($cars->isEmpty())
        <div class="alert alert-light border mb-4">No cars available at this destination yet.</div>
    @else
    <div class="row g-3 mb-4">
        @foreach($cars as $car)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}"
                         class="card-img-top" style="height:140px;object-fit:cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center"
                         style="height:140px;font-size:2.5rem;">🚗</div>
                @endif

                <div class="card-body">
                    <h6 class="fw-bold mb-0">{{ $car->brand }} {{ $car->model }}</h6>
                    <div class="text-muted small mb-2">
                        {{ ucfirst($car->type) }} · {{ $car->seats }} seats
                    </div>

                    <div class="small mb-2">
                        ⚙️ {{ ucfirst($car->transmission) }} · ⛽ {{ ucfirst($car->fuel) }}
                    </div>

                    <div class="fw-bold text-primary">
                        {{ number_format($car->price_per_day, 2) }} DA/day
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0">
                    <button class="btn btn-primary btn-sm w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#addonCar{{ $car->id }}">
                        Rent this car
                    </button>
                </div>
            </div>
        </div>

        {{-- CAR MODAL --}}
        <div class="modal fade" id="addonCar{{ $car->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form action="{{ route('cars.book') }}" method="POST">
                        @csrf
                        <input type="hidden" name="car_rental_id" value="{{ $car->id }}">
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Rent — {{ $car->brand }} {{ $car->model }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pick-up date</label>
                                <input type="date" name="pickup_date" class="form-control"
                                       value="{{ $booking->trip->start_date->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Return date</label>
                                <input type="date" name="return_date" class="form-control"
                                       value="{{ $booking->trip->end_date->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pick-up location</label>
                                <input type="text" name="pickup_location" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Add to my trip
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        @endforeach
    </div>
    @endif

    <div class="text-center mt-2">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            Done — go to my dashboard
        </a>
    </div>

</div>
@endsection