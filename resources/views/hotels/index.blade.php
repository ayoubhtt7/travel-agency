@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-1">🏨 Hotels</h2>
    <p class="text-muted mb-4">Find your perfect stay</p>

    {{-- Filters --}}
    <form method="GET" action="{{ route('hotels.index') }}" class="row g-2 mb-4 align-items-end">
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
            <label class="form-label small fw-semibold">Stars</label>
            <select name="stars" class="form-select">
                <option value="">Any rating</option>
                @foreach([5,4,3,2,1] as $s)
                    <option value="{{ $s }}" {{ request('stars') == $s ? 'selected' : '' }}>
                        {{ str_repeat('★', $s) }}{{ str_repeat('☆', 5 - $s) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Amenity</label>
            <select name="amenity" class="form-select">
                <option value="">Any</option>
                @foreach(['wifi','pool','gym','breakfast','parking','spa','restaurant','airport_shuttle'] as $a)
                    <option value="{{ $a }}" {{ request('amenity') === $a ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $a)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
        @if(request()->hasAny(['destination_id','stars','amenity']))
        <div class="col-md-1">
            <a href="{{ route('hotels.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
        </div>
        @endif
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($hotels->isEmpty())
        <div class="alert alert-info">No hotels found matching your criteria.</div>
    @else
    <div class="row g-4">
        @foreach($hotels as $hotel)
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="row g-0 h-100">
                    <div class="col-4">
                        @if($hotel->image)
                            <img src="{{ asset('storage/' . $hotel->image) }}"
                                 class="img-fluid rounded-start h-100"
                                 style="object-fit:cover;" alt="{{ $hotel->name }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded-start"
                                 style="font-size:2.5rem;">🏨</div>
                        @endif
                    </div>
                    <div class="col-8">
                        <div class="card-body d-flex flex-column h-100">
                            <div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title mb-0">{{ $hotel->name }}</h5>
                                </div>
                                <div class="text-warning mb-1" style="font-size:0.85rem;">
                                    {{ str_repeat('★', $hotel->stars) }}{{ str_repeat('☆', 5 - $hotel->stars) }}
                                </div>
                                @if($hotel->destination)
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $hotel->destination->name }}
                                    </p>
                                @endif

                                @if($hotel->amenities)
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    @foreach(array_slice($hotel->amenities, 0, 4) as $amenity)
                                        <span class="badge bg-light text-dark border" style="font-size:0.7rem;">
                                            {{ ucfirst(str_replace('_', ' ', $amenity)) }}
                                        </span>
                                    @endforeach
                                    @if(count($hotel->amenities) > 4)
                                        <span class="badge bg-light text-muted border" style="font-size:0.7rem;">
                                            +{{ count($hotel->amenities) - 4 }} more
                                        </span>
                                    @endif
                                </div>
                                @endif

                                @php $minPrice = $hotel->rooms->min('price_per_night'); @endphp
                                @if($minPrice)
                                    <div class="mb-2">
                                        <span class="text-muted small">From </span>
                                        <span class="fw-bold text-primary">{{ number_format($minPrice, 2) }} DA</span>
                                        <span class="text-muted small"> / night</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('hotels.show', $hotel) }}"
                                   class="btn btn-primary btn-sm w-100">
                                    View rooms
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
