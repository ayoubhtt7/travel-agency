@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Search summary --}}
    <div class="search-summary mb-4">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div>
                <span class="fs-5 fw-bold">{{ $departureAirport->city }} ({{ $departureAirport->code }})</span>
                <span class="mx-2 text-muted">→</span>
                <span class="fs-5 fw-bold">{{ $arrivalAirport->city }} ({{ $arrivalAirport->code }})</span>
            </div>
            <span class="badge bg-light text-dark border">📅 {{ \Carbon\Carbon::parse($request->departure_date)->format('d M Y') }}</span>
            @if($request->return_date)
                <span class="badge bg-light text-dark border">↩ {{ \Carbon\Carbon::parse($request->return_date)->format('d M Y') }}</span>
            @endif
            <span class="badge bg-light text-dark border">👤 {{ $request->passengers }} passenger(s)</span>
            @php $classMap = ['economique'=>'Economy','eco_premium'=>'Premium Economy','affaires'=>'Business','premiere'=>'First Class']; @endphp
            <span class="badge bg-primary">{{ $classMap[$request->class] ?? $request->class }}</span>
            <a href="{{ route('flights.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">✏️ Modify</a>
        </div>
    </div>

    {{-- ── BOOKABLE FLIGHTS (from DB) ── --}}
    <h4 class="mb-3">
        ✈ Bookable Flights
        <span class="text-muted fs-6">({{ $outboundFlights->count() }} available)</span>
    </h4>

    @forelse($outboundFlights as $flight)
    <div class="flight-card mb-3">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <div class="airline-badge">{{ $flight->airline }}</div>
                <small class="text-muted">{{ $flight->flight_number }}</small>
                <div>
                    @if($flight->is_direct)
                        <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">Direct</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning mt-1">Stopover</span>
                    @endif
                </div>
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
                    {{ $flight->with_baggage ? '🧳 Baggage incl.' : '🚫 No baggage' }}
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->arrival_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->code }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->city }}</div>
            </div>
            <div class="col-md-2 text-center">
                <div class="price-display">{{ number_format($flight->price * $request->passengers, 0, ',', ' ') }} DA</div>
                <small class="text-muted">{{ $request->passengers }} passenger(s)</small>
                <div class="mt-2">
                    @auth
                    <form action="{{ route('flights.passengers') }}" method="GET">
                        <input type="hidden" name="flight_id"  value="{{ $flight->id }}">
                        <input type="hidden" name="passengers" value="{{ $request->passengers }}">
                        <input type="hidden" name="class"      value="{{ $request->class }}">
                        <input type="hidden" name="type"       value="{{ $request->type }}">
                        <button class="btn btn-primary btn-sm w-100">Book →</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">Login to Book</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">No bookable flights found for this route and date.</div>
    @endforelse

    {{-- ── REAL-TIME FLIGHTS FROM API ── --}}
    @if(!empty($liveFlights))
    <hr class="my-4">
    <h4 class="mb-1">
        🌐 Real-Time Flights
        <span class="text-muted fs-6">({{ count($liveFlights) }} from AviationStack)</span>
    </h4>
    <p class="text-muted small mb-3">Live data — for reference only. To book, contact the airline directly or ask admin to add these flights.</p>

    @foreach($liveFlights as $lf)
    @php
        $st = $lf['flight_status'] ?? 'scheduled';
        $badge = \App\Services\AviationStackService::statusBadge($st);
        $depTime = data_get($lf, 'departure.scheduled');
        $arrTime = data_get($lf, 'arrival.scheduled');
        $delay   = data_get($lf, 'departure.delay');
    @endphp
    <div class="flight-card flight-card-live mb-3">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <div class="airline-badge">{{ data_get($lf, 'airline.name', 'Unknown') }}</div>
                <small class="text-muted">{{ data_get($lf, 'flight.iata', '') }}</small>
                <div><span class="badge bg-{{ $badge }} mt-1">{{ ucfirst($st) }}</span></div>
            </div>
            <div class="col-md-3 text-center">
                <div class="time-display">{{ $depTime ? \Carbon\Carbon::parse($depTime)->format('H:i') : '—' }}</div>
                <div class="text-muted small">{{ data_get($lf, 'departure.iata', '') }}</div>
                @if($delay) <span class="badge bg-warning text-dark">+{{ $delay }}m delay</span> @endif
            </div>
            <div class="col-md-2 text-center">
                <div class="flight-line">──────</div>
                @if(!empty(data_get($lf, 'departure.terminal')))
                <div class="text-muted small">T{{ data_get($lf, 'departure.terminal') }}</div>
                @endif
            </div>
            <div class="col-md-3 text-center">
                <div class="time-display">{{ $arrTime ? \Carbon\Carbon::parse($arrTime)->format('H:i') : '—' }}</div>
                <div class="text-muted small">{{ data_get($lf, 'arrival.iata', '') }}</div>
            </div>
            <div class="col-md-2 text-center">
                <a href="{{ route('flights.status', ['flight_number' => data_get($lf, 'flight.iata', '')]) }}"
                   class="btn btn-outline-secondary btn-sm w-100">
                    Track Status
                </a>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- ── RETURN FLIGHTS ── --}}
    @if($request->type === 'aller_retour')
    <hr class="my-4">
    <h4 class="mb-3">↩ Return Flights — {{ $arrivalAirport->city }} → {{ $departureAirport->city }}</h4>

    @forelse($returnFlights as $flight)
    <div class="flight-card mb-3">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <div class="airline-badge">{{ $flight->airline }}</div>
                <small class="text-muted">{{ $flight->flight_number }}</small>
            </div>
            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->departure_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->departureAirport->code }}</div>
            </div>
            <div class="col-md-2 text-center">
                <div class="flight-line">──────</div>
            </div>
            <div class="col-md-3 text-center">
                <div class="time-display">{{ $flight->arrival_at->format('H:i') }}</div>
                <div class="text-muted small">{{ $flight->arrivalAirport->code }}</div>
            </div>
            <div class="col-md-2 text-center">
                <div class="price-display">{{ number_format($flight->price * $request->passengers, 0, ',', ' ') }} DA</div>
                @auth
                <form action="{{ route('flights.passengers') }}" method="GET" class="mt-2">
                    <input type="hidden" name="flight_id"        value="{{ $outboundFlights->first()?->id }}">
                    <input type="hidden" name="return_flight_id" value="{{ $flight->id }}">
                    <input type="hidden" name="passengers"       value="{{ $request->passengers }}">
                    <input type="hidden" name="class"            value="{{ $request->class }}">
                    <input type="hidden" name="type"             value="aller_retour">
                    <button class="btn btn-success btn-sm w-100">Book Round Trip →</button>
                </form>
                @endauth
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">No bookable return flights found.</div>
    @endforelse

    @if(!empty($liveReturnFlights))
    <p class="text-muted small mt-3 mb-2">🌐 Live return flights from AviationStack:</p>
    @foreach($liveReturnFlights as $lf)
    @php $st = $lf['flight_status'] ?? 'scheduled'; $badge = \App\Services\AviationStackService::statusBadge($st); @endphp
    <div class="flight-card flight-card-live mb-2">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <div class="airline-badge">{{ data_get($lf, 'airline.name', 'Unknown') }}</div>
                <small class="text-muted">{{ data_get($lf, 'flight.iata', '') }}</small>
                <div><span class="badge bg-{{ $badge }} mt-1">{{ ucfirst($st) }}</span></div>
            </div>
            <div class="col-md-3 text-center">
                <div class="time-display">{{ data_get($lf, 'departure.scheduled') ? \Carbon\Carbon::parse(data_get($lf, 'departure.scheduled'))->format('H:i') : '—' }}</div>
                <div class="text-muted small">{{ data_get($lf, 'departure.iata', '') }}</div>
            </div>
            <div class="col-md-2 text-center"><div class="flight-line">──────</div></div>
            <div class="col-md-3 text-center">
                <div class="time-display">{{ data_get($lf, 'arrival.scheduled') ? \Carbon\Carbon::parse(data_get($lf, 'arrival.scheduled'))->format('H:i') : '—' }}</div>
                <div class="text-muted small">{{ data_get($lf, 'arrival.iata', '') }}</div>
            </div>
            <div class="col-md-2 text-center">
                <a href="{{ route('flights.status', ['flight_number' => data_get($lf, 'flight.iata', '')]) }}"
                   class="btn btn-outline-secondary btn-sm w-100">Track</a>
            </div>
        </div>
    </div>
    @endforeach
    @endif
    @endif

</div>

@push('styles')
<style>
.search-summary { background:#f0f6ff; border:1px solid #c8deff; border-radius:12px; padding:16px 20px; }
.flight-card { background:white; border:1px solid #e5e7eb; border-radius:12px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.06); transition:box-shadow 0.2s; }
.flight-card:hover { box-shadow:0 4px 20px rgba(0,0,0,0.12); }
.flight-card-live { border-style: dashed; border-color: #9ca3af; background: #fafafa; }
.airline-badge { font-weight:700; font-size:0.85rem; color:#0a2342; }
.time-display { font-size:1.6rem; font-weight:800; color:#0a2342; line-height:1; }
.flight-line { color:#0d6efd; letter-spacing:-3px; }
.price-display { font-size:1.3rem; font-weight:800; color:#0d6efd; }
</style>
@endpush

@endsection
