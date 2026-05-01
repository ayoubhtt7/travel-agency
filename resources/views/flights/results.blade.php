@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Search summary bar --}}
    <div class="search-summary mb-4">
        <div class="d-flex flex-wrap align-items-center gap-3">

            <div>
                <span class="fs-5 fw-bold">{{ $departureAirport->city }} ({{ $departureAirport->code }})</span>
                <span class="mx-2 text-muted">→</span>
                <span class="fs-5 fw-bold">{{ $arrivalAirport->city }} ({{ $arrivalAirport->code }})</span>
            </div>

            {{-- ✅ Optional departure date --}}
            @if($request->departure_date)
                <span class="badge bg-light text-dark border">
                    📅 {{ \Carbon\Carbon::parse($request->departure_date)->format('d M Y') }}
                </span>
            @else
                <span class="badge bg-light text-muted border">
                    📅 Flexible dates
                </span>
            @endif

            {{-- Return date --}}
            @if($request->return_date)
                <span class="badge bg-light text-dark border">
                    ↩ {{ \Carbon\Carbon::parse($request->return_date)->format('d M Y') }}
                </span>
            @endif

            <span class="badge bg-light text-dark border">
                👤 {{ $request->passengers }} passenger(s)
            </span>

            {{-- ✅ Fixed class mapping --}}
            <span class="badge bg-primary">
                @php
                    $classMap = [
                        'economique' => 'Economy',
                        'eco_premium' => 'Premium Economy',
                        'affaires'    => 'Business',
                        'premiere'    => 'First Class'
                    ];
                @endphp

                {{ $classMap[$request->class] ?? ucfirst($request->class) }}
            </span>

            <a href="{{ route('flights.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
                ✏️ Modify Search
            </a>
        </div>
    </div>

    {{-- Optional info --}}
    @if(!$request->departure_date)
    <div class="alert alert-info">
        Showing all available flights (no specific date selected).
    </div>
    @endif

    {{-- OUTBOUND --}}
    <h4 class="mb-3">
        ✈ Outbound Flights — {{ $departureAirport->city }} → {{ $arrivalAirport->city }}
        <span class="text-muted fs-6">({{ $outboundFlights->count() }} result(s))</span>
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
                <div class="text-muted small">{{ $flight->departureAirport->code }}</div>
                <div class="text-muted small">{{ $flight->departureAirport->city }}</div>
            </div>

            <div class="col-md-2 text-center">
                <div class="text-muted small mb-1">{{ $flight->duration }}</div>
                <div class="flight-line">──────</div>
                <div class="text-muted small mt-1">
                    {{ $flight->with_baggage ? '🧳 Baggage included' : '🚫 No baggage' }}
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->arrival_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->code }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->city }}</div>
            </div>

            <div class="col-md-2 text-center">
                <div class="price-display">
                    {{ number_format($flight->price * $request->passengers, 0, ',', ' ') }} DA
                </div>

                <small class="text-muted">{{ $request->passengers }} passenger(s)</small>

                <div class="mt-2">
                    @auth
                    <form action="{{ route('flights.book') }}" method="POST">
                        @csrf
                        <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                        <input type="hidden" name="passengers" value="{{ $request->passengers }}">
                        <input type="hidden" name="class" value="{{ $request->class }}">
                        <input type="hidden" name="type" value="{{ $request->type }}">
                        <button class="btn btn-primary btn-sm w-100">Book Now</button>
                    </form>
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
        No flights available. <a href="{{ route('flights.index') }}">Modify search</a>.
    </div>
    @endforelse

    {{-- RETURN --}}
    @if($request->type === 'aller_retour' && $returnFlights->isNotEmpty())
    <hr class="my-4">

    <h4 class="mb-3">
        ↩ Return Flights — {{ $arrivalAirport->city }} → {{ $departureAirport->city }}
        <span class="text-muted fs-6">({{ $returnFlights->count() }} result(s))</span>
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
                <div class="text-muted small">{{ $flight->departureAirport->code }}</div>
                <div class="text-muted small">{{ $flight->departureAirport->city }}</div>
            </div>

            <div class="col-md-2 text-center">
                <div class="text-muted small">{{ $flight->duration }}</div>
                <div class="flight-line">──────</div>
            </div>

            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->arrival_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->code }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->city }}</div>
            </div>

            <div class="col-md-2 text-center">
                <div class="price-display">
                    {{ number_format($flight->price * $request->passengers, 0, ',', ' ') }} DA
                </div>

                <div class="mt-2">
                    @auth
                    <form action="{{ route('flights.book') }}" method="POST">
                        @csrf
                        <input type="hidden" name="flight_id" value="{{ $outboundFlights->first()?->id }}">
                        <input type="hidden" name="return_flight_id" value="{{ $flight->id }}">
                        <input type="hidden" name="passengers" value="{{ $request->passengers }}">
                        <input type="hidden" name="class" value="{{ $request->class }}">
                        <input type="hidden" name="type" value="aller_retour">
                        <button class="btn btn-success btn-sm w-100">Book Round Trip</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">
                        Login to Book
                    </a>
                    @endauth
                </div>
            </div>

        </div>
    </div>
    @endforeach

    @elseif($request->type === 'aller_retour' && $returnFlights->isEmpty())
    <div class="alert alert-warning mt-3">
        No return flights available.
    </div>
    @endif

</div>

<style>
.search-summary {
    background: #f0f6ff;
    border-radius: 12px;
    padding: 16px;
}
.flight-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.time-display {
    font-size: 1.6rem;
    font-weight: bold;
}
.price-display {
    font-size: 1.3rem;
    font-weight: bold;
    color: #0d6efd;
}
</style>

@endsection