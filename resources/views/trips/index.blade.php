@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Available Trips</h2>
        <form method="GET" action="{{ route('trips.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search trips..."
                   value="{{ request('search') }}">
            <button class="btn btn-outline-primary">Search</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        @forelse($trips as $trip)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                @if($trip->image)
                    <img src="{{ asset('storage/' . $trip->image) }}" class="card-img-top" style="height:200px;object-fit:cover;" alt="{{ $trip->title }}">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:200px;">
                        <span>No Image</span>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $trip->title }}</h5>
                    <p class="text-muted small mb-1">
                        📍 {{ $trip->destination->name ?? 'Unknown' }}, {{ $trip->destination->country ?? '' }}
                    </p>
                    <p class="text-muted small mb-2">
                        📅 {{ $trip->start_date?->format('d M Y') }} — {{ $trip->end_date?->format('d M Y') }}
                    </p>
                    <p class="card-text flex-grow-1">{{ Str::limit($trip->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fw-bold text-primary fs-5">{{ number_format($trip->price, 2) }} DA</span>
                        <span class="badge bg-{{ $trip->available_seats > 0 ? 'success' : 'danger' }}">
                            {{ $trip->available_seats > 0 ? $trip->available_seats . ' seats left' : 'Full' }}
                        </span>
                    </div>
                    <a href="{{ route('trips.show', $trip->id) }}" class="btn btn-primary mt-3">View Details</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">No trips found.</div>
        </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $trips->withQueryString()->links() }}
    </div>

</div>

@endsection
