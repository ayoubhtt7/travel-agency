@extends('layouts.app')

@section('content')

<div class="container py-4">

    <a href="{{ route('trips.index') }}" class="btn btn-outline-secondary mb-3">&larr; Back to Trips</a>

    <div class="row g-4">
        {{-- Trip Details --}}
        <div class="col-md-8">
            @if($trip->image)
                <img src="{{ asset('storage/' . $trip->image) }}" class="img-fluid rounded mb-4"
                     style="width:100%;height:350px;object-fit:cover;" alt="{{ $trip->title }}">
            @endif

            <h1>{{ $trip->title }}</h1>
            <p class="text-muted">
                📍 {{ $trip->destination->name ?? 'Unknown' }}, {{ $trip->destination->country ?? '' }}
            </p>

            <div class="row text-center my-3">
                <div class="col">
                    <small class="text-muted d-block">Start Date</small>
                    <strong>{{ $trip->start_date?->format('d M Y') }}</strong>
                </div>
                <div class="col">
                    <small class="text-muted d-block">End Date</small>
                    <strong>{{ $trip->end_date?->format('d M Y') }}</strong>
                </div>
                <div class="col">
                    <small class="text-muted d-block">Duration</small>
                    <strong>{{ $trip->duration }} days</strong>
                </div>
                <div class="col">
                    <small class="text-muted d-block">Seats Left</small>
                    <strong class="text-{{ $trip->available_seats > 0 ? 'success' : 'danger' }}">
                        {{ $trip->available_seats }}
                    </strong>
                </div>
            </div>

            <hr>
            <h5>About this trip</h5>
            <p>{{ $trip->description }}</p>

            {{-- Reviews --}}
            @if($trip->reviews->isNotEmpty())
            <hr>
            <h5>Reviews ({{ $trip->reviews->count() }})</h5>
            @foreach($trip->reviews as $review)
            <div class="card mb-2 p-3">
                <div class="d-flex justify-content-between">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="text-warning">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                </div>
                <p class="mb-0 mt-1 text-muted">{{ $review->comment }}</p>
            </div>
            @endforeach
            @endif
        </div>

        {{-- Booking Card --}}
        <div class="col-md-4">
            <div class="card shadow p-4 sticky-top" style="top: 80px;">
                <h4 class="text-center mb-3">Book This Trip</h4>
                <h3 class="text-primary text-center">{{ number_format($trip->price, 2) }} DA <small class="fs-6 text-muted">/ person</small></h3>

                @if($trip->available_seats <= 0)
                    <div class="alert alert-danger text-center mt-3">This trip is fully booked.</div>
                @elseif(auth()->check())
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('book.store', $trip->id) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Number of Persons</label>
                            <input type="number" name="number_of_persons" class="form-control @error('number_of_persons') is-invalid @enderror"
                                   min="1" max="{{ $trip->available_seats }}" value="{{ old('number_of_persons', 1) }}" required>
                            @error('number_of_persons')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
                    </form>
                @else
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login to Book</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
