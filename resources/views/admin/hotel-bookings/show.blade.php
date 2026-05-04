@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:800px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🏨 Hotel Booking #{{ $hotelBooking->id }}</h2>
        <a href="{{ route('admin.hotel-bookings.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">

        {{-- Customer --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">👤 Customer</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $hotelBooking->user->name ?? 'Deleted' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $hotelBooking->user->email ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Booked on:</strong> {{ $hotelBooking->created_at->format('d M Y \a\t H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">📋 Summary</div>
                <div class="card-body">
                    <p class="mb-1">
                        <strong>Check-in:</strong> {{ $hotelBooking->check_in->format('d M Y') }}
                    </p>
                    <p class="mb-1">
                        <strong>Check-out:</strong> {{ $hotelBooking->check_out->format('d M Y') }}
                    </p>
                    <p class="mb-1">
                        <strong>Duration:</strong> {{ $hotelBooking->nights }} night{{ $hotelBooking->nights != 1 ? 's' : '' }}
                    </p>
                    <p class="mb-1">
                        <strong>Guests:</strong> {{ $hotelBooking->guests }}
                    </p>
                    <p class="mb-1">
                        <strong>Total:</strong>
                        <span class="fw-bold text-primary fs-5">{{ number_format($hotelBooking->total_price, 2) }} DA</span>
                    </p>
                    <p class="mb-0">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $hotelBooking->status === 'confirmed' ? 'success' : ($hotelBooking->status === 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($hotelBooking->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Hotel & Room --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">🏨 Hotel & Room</div>
                <div class="card-body">
                    @if($hotelBooking->hotel)
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            @if($hotelBooking->hotel->image)
                                <img src="{{ asset('storage/' . $hotelBooking->hotel->image) }}"
                                     class="img-fluid rounded" style="height:100px;object-fit:cover;"
                                     alt="{{ $hotelBooking->hotel->name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="height:100px;font-size:2.5rem;">🏨</div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <h5 class="mb-1">{{ $hotelBooking->hotel->name }}</h5>
                            <div class="text-warning mb-1">{{ str_repeat('★', $hotelBooking->hotel->stars) }}</div>
                            @if($hotelBooking->hotel->destination)
                                <div class="text-muted small">
                                    📍 {{ $hotelBooking->hotel->destination->name }},
                                    {{ $hotelBooking->hotel->destination->country }}
                                </div>
                            @endif
                            <div class="text-muted small">{{ $hotelBooking->hotel->address }}</div>
                        </div>
                        <div class="col-md-4">
                            @if($hotelBooking->room)
                            <div class="border rounded p-3">
                                <div class="fw-bold mb-1">{{ ucfirst($hotelBooking->room->type) }} Room</div>
                                <div class="small text-muted">👤 Up to {{ $hotelBooking->room->capacity }} guests</div>
                                <div class="small mt-1 d-flex flex-wrap gap-1">
                                    @if($hotelBooking->room->with_breakfast)
                                        <span class="badge bg-success">🍳 Breakfast</span>
                                    @endif
                                    @if($hotelBooking->room->refundable)
                                        <span class="badge bg-info text-dark">Refundable</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Non-refundable</span>
                                    @endif
                                </div>
                                <div class="fw-bold text-primary mt-2">
                                    {{ number_format($hotelBooking->room->price_per_night, 2) }} DA/night
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                        <p class="text-muted mb-0">Hotel has been deleted.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Linked trip booking --}}
        @if($hotelBooking->tripBooking)
        <div class="col-12">
            <div class="card shadow-sm border-info">
                <div class="card-header fw-bold">🔗 Linked Trip Booking</div>
                <div class="card-body">
                    <p class="mb-1">
                        <strong>Trip:</strong> {{ $hotelBooking->tripBooking->trip->title ?? 'Deleted trip' }}
                    </p>
                    <p class="mb-0">
                        <a href="{{ route('admin.bookings.show', $hotelBooking->tripBooking) }}"
                           class="btn btn-sm btn-outline-info">
                            View Trip Booking #{{ $hotelBooking->tripBooking->id }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Change status --}}
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header fw-bold">⚙ Change Status</div>
                <div class="card-body">
                    <form action="{{ route('admin.hotel-bookings.update', $hotelBooking) }}"
                          method="POST" class="d-flex gap-2 align-items-center">
                        @csrf @method('PUT')
                        <select name="status" class="form-select" style="max-width:200px;">
                            <option value="pending"   {{ $hotelBooking->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $hotelBooking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $hotelBooking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button class="btn btn-warning">Save</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
