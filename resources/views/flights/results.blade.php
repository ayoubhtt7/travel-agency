@extends('layouts.app')

@section('content')
<div class="container py-4">

@php
    // SAFE CLASS MAP (DB MATCHED)
    $classMap = [
        'economique' => 'Economy',
        'eco_premium' => 'Premium Economy',
        'affaires' => 'Business',
        'premiere' => 'First Class'
    ];

    $class = $request->class ?? 'economique';
    $type  = $request->type ?? 'aller_simple';
@endphp

    {{-- Search summary bar --}}
    <div class="search-summary mb-4">
        <div class="d-flex flex-wrap align-items-center gap-3">

            <div>
                <span class="fs-5 fw-bold">
                    {{ $departureAirport->city }} ({{ $departureAirport->code }})
                </span>
                <span class="mx-2 text-muted">→</span>
                <span class="fs-5 fw-bold">
                    {{ $arrivalAirport->city }} ({{ $arrivalAirport->code }})
                </span>
            </div>

            @if($request->departure_date)
                <span class="badge bg-light text-dark border">
                    📅 {{ \Carbon\Carbon::parse($request->departure_date)->format('d M Y') }}
                </span>
            @else
                <span class="badge bg-light text-muted border">📅 Flexible dates</span>
            @endif

            @if($request->return_date)
                <span class="badge bg-light text-dark border">
                    ↩ {{ \Carbon\Carbon::parse($request->return_date)->format('d M Y') }}
                </span>
            @endif

            <span class="badge bg-light text-dark border">
                👤 {{ $request->passengers }} passenger(s)
            </span>

            <span class="badge bg-primary">
                {{ $classMap[$class] ?? $class }}
            </span>

            <a href="{{ route('flights.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
                ✏️ Modify Search
            </a>
        </div>
    </div>

    @if(!$request->departure_date)
        <div class="alert alert-info">
            Showing all available flights (no specific date selected).
        </div>
    @endif

    {{-- OUTBOUND --}}
    <h4 class="mb-3">
        ✈ Outbound Flights — {{ $departureAirport->city }} → {{ $arrivalAirport->city }}
        <span class="text-muted fs-6">
            ({{ $outboundFlights->count() }} result(s))
        </span>
    </h4>

    @forelse($outboundFlights as $flight)
    <div class="flight-card mb-3">
        <div class="row align-items-center">

            <div class="col-md-2 text-center">
                <div class="airline-badge">{{ $flight->airline }}</div>
                <small class="text-muted">{{ $flight->flight_number }}</small>

                @if($flight->is_direct)
                    <div><span class="badge bg-success-subtle text-success mt-1">Direct</span></div>
                @else
                    <div><span class="badge bg-warning-subtle text-warning mt-1">Stopover</span></div>
                @endif
            </div>

            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->departure_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->departure_at->format('d M Y') }}</div>
                <div class="text-muted small">{{ $flight->departureAirport->code ?? '' }}</div>
                <div class="text-muted small">{{ $flight->departureAirport->city ?? '' }}</div>
            </div>

            <div class="col-md-2 text-center">
                <div class="text-muted small mb-1">{{ $flight->duration }}</div>
                <div class="flight-line">──────</div>
                <div class="text-muted small">
                    {{ $flight->with_baggage ? '🧳 Baggage included' : '🚫 No baggage' }}
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->arrival_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->arrival_at->format('d M Y') }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->code ?? '' }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->city ?? '' }}</div>
            </div>

            <div class="col-md-2 text-center">
                <div class="price-display">
                    {{ number_format($flight->price * $request->passengers, 0, ',', ' ') }} DA
                </div>

                <small class="text-muted">
                    {{ $request->passengers }} passenger(s)
                </small>

                <div class="mt-2">
                    @auth
                        <a href="{{ route('flights.passengers', $flight->id) }}?passengers={{ $request->passengers }}&class={{ $request->class }}&type={{ $request->type }}"
                           class="btn btn-primary btn-sm w-100">
                            Book Now →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">
                            Login to Book
                        </a>
                    @endauth
                </div>
            </div>

        </div>
    </div>
    @empty
        <div class="alert alert-info">
            No flights available.
        </div>
    @endforelse


    {{-- RETURN --}}
    @if($type === 'aller_retour' && isset($returnFlights) && $returnFlights->isNotEmpty())
    <hr class="my-4">

    <h4 class="mb-3">
        ↩ Return Flights — {{ $arrivalAirport->city }} → {{ $departureAirport->city }}
    </h4>

    @foreach($returnFlights as $flight)
    <div class="flight-card mb-3">
        <div class="row align-items-center">

            <div class="col-md-2 text-center">
                <div class="airline-badge">{{ $flight->airline }}</div>
                <small>{{ $flight->flight_number }}</small>
            </div>

            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->departure_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->departure_at->format('d M Y') }}</div>
            </div>

            <div class="col-md-2 text-center">
                {{ $flight->duration }}
            </div>

            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->arrival_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->arrival_at->format('d M Y') }}</div>
            </div>

            <div class="col-md-2 text-center">
                <div class="price-display">
                    {{ number_format($flight->price * $request->passengers, 0, ',', ' ') }} DA
                </div>

                @auth
                    <a href="{{ route('flights.passengers', $flight->id) }}?flight_id={{ $outboundFlights->first()?->id }}&return_flight_id={{ $flight->id }}&passengers={{ $request->passengers }}&class={{ $class }}&type={{ $type }}"
                       class="btn btn-success btn-sm w-100 mt-2">
                        Book Round Trip →
                    </a>
                @endauth
            </div>

        </div>
    </div>
    @endforeach
    @endif

</div>
@endsection