@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 800px;">

    <h2 class="mb-4">✈ Live Flight Status</h2>

    {{-- Search form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('flights.status') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="flight_number"
                       class="form-control"
                       placeholder="Enter flight number (e.g. AH1024)"
                       value="{{ $flightNumber }}" required>
                <button type="submit" class="btn btn-primary px-4">Track</button>
            </form>
        </div>
    </div>

    @if($error)
        <div class="alert alert-warning">{{ $error }}</div>

    @elseif(!empty($status))
        @php
            $dep    = $status['departure'] ?? [];
            $arr    = $status['arrival'] ?? [];
            $flight = $status['flight'] ?? [];
            $airline= $status['airline'] ?? [];
            $st     = $status['flight_status'] ?? 'unknown';
            $badgeColor = \App\Services\AviationStackService::statusBadge($st);
        @endphp

        {{-- Status header --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="mb-0 fw-bold">{{ $airline['name'] ?? 'Unknown Airline' }}</h4>
                        <span class="text-muted">Flight {{ $flight['iata'] ?? $flightNumber }}</span>
                    </div>
                    <span class="badge bg-{{ $badgeColor }} fs-6 px-3 py-2">
                        {{ ucfirst($st) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Route card --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row text-center">

                    {{-- Departure --}}
                    <div class="col-5">
                        <div class="fs-2 fw-bold">{{ $dep['iata'] ?? '—' }}</div>
                        <div class="text-muted">{{ $dep['airport'] ?? '' }}</div>
                        <div class="mt-2">
                            <small class="text-muted d-block">Scheduled</small>
                            <strong>{{ isset($dep['scheduled']) ? \Carbon\Carbon::parse($dep['scheduled'])->format('H:i') : '—' }}</strong>
                        </div>
                        @if(!empty($dep['actual']))
                        <div class="mt-1">
                            <small class="text-muted d-block">Actual</small>
                            <strong class="text-success">{{ \Carbon\Carbon::parse($dep['actual'])->format('H:i') }}</strong>
                        </div>
                        @endif
                        @if(!empty($dep['delay']))
                        <div class="mt-1">
                            <span class="badge bg-warning text-dark">Delay: {{ $dep['delay'] }} min</span>
                        </div>
                        @endif
                        @if(!empty($dep['terminal']))
                        <small class="text-muted">Terminal {{ $dep['terminal'] }}
                            @if(!empty($dep['gate'])) · Gate {{ $dep['gate'] }} @endif
                        </small>
                        @endif
                    </div>

                    {{-- Center --}}
                    <div class="col-2 d-flex flex-column align-items-center justify-content-center">
                        <div class="text-primary fs-4">✈</div>
                        <div class="text-muted small mt-1">
                            @if(!empty($status['flight_date']))
                                {{ \Carbon\Carbon::parse($status['flight_date'])->format('d M Y') }}
                            @endif
                        </div>
                    </div>

                    {{-- Arrival --}}
                    <div class="col-5">
                        <div class="fs-2 fw-bold">{{ $arr['iata'] ?? '—' }}</div>
                        <div class="text-muted">{{ $arr['airport'] ?? '' }}</div>
                        <div class="mt-2">
                            <small class="text-muted d-block">Scheduled</small>
                            <strong>{{ isset($arr['scheduled']) ? \Carbon\Carbon::parse($arr['scheduled'])->format('H:i') : '—' }}</strong>
                        </div>
                        @if(!empty($arr['actual']))
                        <div class="mt-1">
                            <small class="text-muted d-block">Actual</small>
                            <strong class="text-success">{{ \Carbon\Carbon::parse($arr['actual'])->format('H:i') }}</strong>
                        </div>
                        @endif
                        @if(!empty($arr['delay']))
                        <div class="mt-1">
                            <span class="badge bg-warning text-dark">Delay: {{ $arr['delay'] }} min</span>
                        </div>
                        @endif
                        @if(!empty($arr['terminal']))
                        <small class="text-muted">Terminal {{ $arr['terminal'] }}
                            @if(!empty($arr['baggage'])) · Baggage {{ $arr['baggage'] }} @endif
                        </small>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Aircraft info --}}
        @if(!empty($status['aircraft']))
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Aircraft</h6>
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Registration</small>
                        <strong>{{ $status['aircraft']['registration'] ?? '—' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">IATA Type</small>
                        <strong>{{ $status['aircraft']['iata'] ?? '—' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">ICAO Type</small>
                        <strong>{{ $status['aircraft']['icao'] ?? '—' }}</strong>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @else
        <div class="alert alert-info">
            Enter a flight number above to check its live status.
        </div>
    @endif

</div>
@endsection
