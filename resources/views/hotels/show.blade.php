@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:900px;">

    {{-- Hotel Header --}}
    <div class="row g-4 mb-4">
        <div class="col-md-5">
            @if($hotel->image)
                <img src="{{ asset('storage/' . $hotel->image) }}"
                     class="img-fluid rounded-3 w-100"
                     style="height:280px;object-fit:cover;" alt="{{ $hotel->name }}">
            @else
                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center"
                     style="height:280px;font-size:4rem;">🏨</div>
            @endif
        </div>
        <div class="col-md-7">
            <div class="text-warning mb-1">
                {{ str_repeat('★', $hotel->stars) }}{{ str_repeat('☆', 5 - $hotel->stars) }}
                <span class="text-muted small ms-1">{{ $hotel->stars }}-star hotel</span>
            </div>
            <h2 class="mb-1">{{ $hotel->name }}</h2>
            @if($hotel->destination)
                <p class="text-muted mb-2">
                    <i class="bi bi-geo-alt me-1"></i>{{ $hotel->destination->name }}, {{ $hotel->destination->country }}
                </p>
            @endif
            <p class="text-muted small mb-3">
                <i class="bi bi-building me-1"></i>{{ $hotel->address }}
            </p>

            @if($hotel->description)
                <p class="mb-3">{{ $hotel->description }}</p>
            @endif

            @if($hotel->amenities)
            <div class="d-flex flex-wrap gap-2">
                @foreach($hotel->amenities as $amenity)
                    @php
                        $icons = ['wifi'=>'📶','pool'=>'🏊','gym'=>'💪','breakfast'=>'🍳',
                                  'parking'=>'🅿️','spa'=>'💆','restaurant'=>'🍽️','airport_shuttle'=>'🚌'];
                    @endphp
                    <span class="badge bg-light text-dark border px-2 py-1">
                        {{ $icons[$amenity] ?? '✓' }} {{ ucfirst(str_replace('_', ' ', $amenity)) }}
                    </span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Rooms --}}
    <h4 class="mb-3">Available Rooms</h4>

    @if($hotel->rooms->isEmpty())
        <div class="alert alert-info">No rooms configured yet.</div>
    @else
    <div class="row g-3">
        @foreach($hotel->rooms as $room)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            @if($room->image)
                                <img src="{{ asset('storage/' . $room->image) }}"
                                     class="img-fluid rounded" style="height:80px;object-fit:cover;"
                                     alt="{{ $room->type }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="height:80px;font-size:2rem;">🛏️</div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <h6 class="mb-1 fw-bold">{{ ucfirst($room->type) }} Room</h6>
                            <div class="text-muted small">
                                👤 Up to {{ $room->capacity }} guest{{ $room->capacity > 1 ? 's' : '' }}
                            </div>
                            <div class="d-flex gap-2 mt-1 flex-wrap">
                                @if($room->with_breakfast)
                                    <span class="badge bg-success">🍳 Breakfast included</span>
                                @endif
                                @if($room->refundable)
                                    <span class="badge bg-info text-dark">✓ Refundable</span>
                                @else
                                    <span class="badge bg-warning text-dark">Non-refundable</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-bold text-primary fs-5">{{ number_format($room->price_per_night, 2) }} DA</div>
                            <div class="text-muted small">per night</div>
                            <div class="badge bg-{{ $room->available_rooms > 2 ? 'success' : 'warning text-dark' }} mt-1">
                                {{ $room->available_rooms }} room{{ $room->available_rooms != 1 ? 's' : '' }} left
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            @auth
                            @if($room->available_rooms > 0)
                            <button class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#bookRoom{{ $room->id }}">
                                Book this room
                            </button>
                            @else
                            <button class="btn btn-secondary" disabled>Unavailable</button>
                            @endif
                            @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">Login to book</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Room Booking Modal --}}
        @auth
        <div class="modal fade" id="bookRoom{{ $room->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('hotels.book') }}" method="POST">
                        @csrf
                        <input type="hidden" name="hotel_room_id" value="{{ $room->id }}">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Book — {{ ucfirst($room->type) }} Room at {{ $hotel->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Check-in</label>
                                    <input type="date" name="check_in" class="form-control"
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Check-out</label>
                                    <input type="date" name="check_out" class="form-control"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Guests</label>
                                    <input type="number" name="guests" class="form-control"
                                           min="1" max="{{ $room->capacity }}"
                                           placeholder="Max {{ $room->capacity }}" required>
                                </div>
                            </div>
                            <div class="alert alert-info small mt-3 mb-0">
                                <strong>{{ number_format($room->price_per_night, 2) }} DA / night</strong>
                                — total calculated on confirmation.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm Booking</button>
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
