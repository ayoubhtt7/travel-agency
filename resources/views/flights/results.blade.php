@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Search summary --}}
    <div class="search-summary mb-4">
        <div class="d-flex flex-wrap align-items-center gap-3">

            <div>
                <span class="fw-bold fs-5">{{ $departureAirport->city }} ({{ $departureAirport->code }})</span>
                <span class="mx-2">→</span>
                <span class="fw-bold fs-5">{{ $arrivalAirport->city }} ({{ $arrivalAirport->code }})</span>
            </div>

            <span class="badge bg-light text-dark border">
                📅 {{ $request->departure_date ? \Carbon\Carbon::parse($request->departure_date)->format('d M Y') : 'Flexible' }}
            </span>

            @if($request->return_date)
                <span class="badge bg-light text-dark border">
                    ↩ {{ \Carbon\Carbon::parse($request->return_date)->format('d M Y') }}
                </span>
            @endif

            <span class="badge bg-light text-dark border">
                👤 {{ $request->passengers }} passenger(s)
            </span>

            <span class="badge bg-primary">
                {{ ucfirst(str_replace('_', ' ', $request->class)) }}
            </span>

            <a href="{{ route('flights.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
                Modify
            </a>

        </div>
    </div>

    {{-- OUTBOUND --}}
    <h4 class="mb-3">
        ✈ Outbound Flights ({{ $outboundFlights->count() }})
    </h4>

    @forelse($outboundFlights as $flight)
    <div class="flight-card mb-3">
        <div class="row align-items-center">

            <div class="col-md-2 text-center">
                <strong>{{ $flight->airline }}</strong><br>
                <small>{{ $flight->flight_number }}</small>

                <div class="mt-1">
                    @if($flight->is_direct)
                        <span class="badge bg-success">Direct</span>
                    @else
                        <span class="badge bg-warning">Stop</span>
                    @endif
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="time">{{ $flight->departure_at->format('H:i') }}</div>
                <small>{{ $flight->departureAirport->code }}</small>
            </div>

            <div class="col-md-2 text-center">
                <small>{{ $flight->with_baggage ? '🧳 Baggage' : 'No baggage' }}</small>
            </div>

            <div class="col-md-3 text-center">
                <div class="time">{{ $flight->arrival_at->format('H:i') }}</div>
                <small>{{ $flight->arrivalAirport->code }}</small>
            </div>

            <div class="col-md-2 text-center">
                <div class="price">
                    {{ number_format($flight->price * $request->passengers, 0) }} DA
                </div>

                @auth
                <a href="{{ route('flights.passengers', [
                    'flight_id'  => $flight->id,
                    'passengers' => $request->passengers,
                    'class'      => $request->class,
                    'type'       => $request->type,
                ]) }}" class="btn btn-primary btn-sm w-100 mt-2">
                    Book Now
                </a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                    Login
                </a>
                @endauth
            </div>

        </div>
    </div>
    @empty
        <div class="alert alert-info">No flights found.</div>
    @endforelse


    {{-- RETURN --}}
    @if($request->type === 'roundtrip')

        <hr class="my-4">

        <h4>↩ Return Flights ({{ $returnFlights->count() }})</h4>

        @forelse($returnFlights as $flight)
        <div class="flight-card mb-3">
            <div class="row align-items-center">

                <div class="col-md-2 text-center">
                    <strong>{{ $flight->airline }}</strong><br>
                    <small>{{ $flight->flight_number }}</small>
                </div>

                <div class="col-md-3 text-center">
                    {{ $flight->departure_at->format('H:i') }}
                </div>

                <div class="col-md-3 text-center">
                    {{ $flight->arrival_at->format('H:i') }}
                </div>

                <div class="col-md-2 text-center">
                    {{ number_format($flight->price * $request->passengers, 0) }} DA
                </div>

                <div class="col-md-2 text-center">
                    @auth
                    <a href="{{ route('flights.passengers', [
                        'flight_id'        => $outboundFlights->first()?->id,
                        'return_flight_id' => $flight->id,
                        'passengers'       => $request->passengers,
                        'class'            => $request->class,
                        'type'             => 'roundtrip',
                    ]) }}" class="btn btn-success btn-sm w-100">
                        Book Round Trip
                    </a>
                    @endauth
                </div>

            </div>
        </div>
        @empty
            <div class="alert alert-warning">No return flights found.</div>
        @endforelse

    @endif

</div>

<style>
.flight-card {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.time { font-size: 1.4rem; font-weight: bold; }
.price { font-weight: bold; color: #0d6efd; }
.search-summary {
    background: #f5f9ff;
    padding: 15px;
    border-radius: 10px;
}
</style>

@endsection