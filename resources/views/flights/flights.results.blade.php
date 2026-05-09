@extends('layouts.app')

@push('styles')
<style>
    /* ── Page background ── */
    .results-page { background: #f1f5f9; min-height: 100%; }

    /* ── Search summary bar ── */
    .search-summary {
        background: #fff;
        border-radius: 14px;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
    }

    /* ── Section heading ── */
    .section-heading {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        padding: .5rem 0;
        border-left: 4px solid #3b82f6;
        padding-left: .75rem;
        margin-bottom: 1rem;
    }
    .section-heading.return { border-color: #10b981; }

    /* ── Flight card ── */
    .flight-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,.07);
        border: 1px solid #e2e8f0;
        transition: box-shadow .2s, transform .15s;
    }
    .flight-card:hover {
        box-shadow: 0 6px 24px rgba(59,130,246,.13);
        transform: translateY(-2px);
    }

    /* ── Airline badge ── */
    .airline-badge {
        display: inline-block;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: .85rem;
        padding: .3rem .75rem;
        border-radius: 8px;
        border: 1px solid #bfdbfe;
        margin-bottom: .3rem;
    }

    /* ── Time display ── */
    .time-display {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    /* ── Flight line with arrow ── */
    .flight-line {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        color: #94a3b8;
        font-size: .8rem;
        margin: .25rem 0;
    }
    .flight-line::before,
    .flight-line::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #cbd5e1;
    }

    /* ── Duration pill ── */
    .duration-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: .2rem .75rem;
        font-size: .78rem;
        color: #64748b;
        font-weight: 600;
        display: inline-block;
        margin-bottom: .35rem;
    }

    /* ── Price ── */
    .price-display {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1d4ed8;
        line-height: 1.1;
    }

    /* ── Book button ── */
    .btn-book {
        background: linear-gradient(90deg, #2563eb, #1d4ed8);
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: .9rem;
        padding: .5rem 1rem;
        transition: opacity .2s, transform .15s;
    }
    .btn-book:hover { opacity: .92; transform: translateY(-1px); }

    .btn-book-return {
        background: linear-gradient(90deg, #059669, #047857);
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: .9rem;
        padding: .5rem 1rem;
        transition: opacity .2s;
    }
    .btn-book-return:hover { opacity: .9; }

    /* ── Divider between outbound / return ── */
    .section-divider {
        height: 3px;
        background: linear-gradient(90deg, #10b981, transparent);
        border: none;
        border-radius: 2px;
        margin: 2rem 0 1.5rem;
    }

    /* ── Airport code chip ── */
    .airport-code {
        font-size: .95rem;
        font-weight: 700;
        color: #334155;
    }
    .airport-city {
        font-size: .78rem;
        color: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="results-page">
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
    <h4 class="section-heading">
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
                <div class="duration-pill mb-1">{{ $flight->duration }}</div>
                <div class="flight-line">✈</div>
                <div class="text-muted small">
                    {{ $flight->with_baggage ? '🧳 Baggage included' : '🚫 No baggage' }}
                </div>
            </div>

            <div class="col-md-3 text-center">
                @if($flight->return_departure_at)
                    <div class="time-display">{{ \Carbon\Carbon::parse($flight->return_departure_at)->format('H:i') }}</div>
                    <div class="text-muted small">{{ \Carbon\Carbon::parse($flight->return_departure_at)->format('d M Y') }}</div>
                @else
                    <div class="time-display">{{ $flight->arrival_at->format('H:i') }}</div>
                    <div class="text-muted small">{{ $flight->arrival_at->format('d M Y') }}</div>
                @endif
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
                           class="btn btn-primary btn-book text-white w-100">
                            Book Now →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
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
    <hr class="section-divider">

    <h4 class="section-heading return">
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
                <div class="duration-pill">{{ $flight->duration }}</div>
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
                       class="btn btn-book-return text-white w-100 mt-2">
                        Book Round Trip →
                    </a>
                @endauth
            </div>

        </div>
    </div>
    @endforeach
    @endif

</div>
</div>
@endsection