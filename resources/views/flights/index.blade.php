@extends('layouts.app')

@section('content')

<div class="flights-hero">
    <div class="container py-5">

        <h1 class="text-white fw-bold mb-2 text-center">Book Your Flight with Confidence</h1>
        <p class="text-white text-center mb-4 opacity-75">Best prices to all destinations</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="flight-card">

            {{-- Type tabs --}}
            <ul class="nav nav-tabs border-0 mb-4" id="flightTypeTabs">
                <li class="nav-item">
                    <button class="nav-link active tab-btn" data-type="aller_retour" type="button">
                        ✈ Round Trip
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link tab-btn" data-type="aller_simple" type="button">
                        → One Way
                    </button>
                </li>
            </ul>

            <form action="{{ route('flights.search') }}" method="GET" id="flightSearchForm">
                <input type="hidden" name="type" id="flightType" value="aller_retour">

                <div class="row g-3">

                    {{-- Departure --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <span class="text-primary">✈</span> Departure
                        </label>
                        <select name="departure_code" class="form-select" required>
                            <option value="">-- Departure Airport --</option>
                            @php $currentCountry = null; @endphp
                            @foreach($airports as $airport)
                                @if($currentCountry !== $airport->country)
                                    @if($currentCountry !== null)</optgroup>@endif
                                    <optgroup label="{{ $airport->country }}">
                                    @php $currentCountry = $airport->country; @endphp
                                @endif
                                <option value="{{ $airport->code }}">
                                    {{ $airport->city }} ({{ $airport->code }}) – {{ $airport->name }}
                                </option>
                            @endforeach
                            </optgroup>
                        </select>
                    </div>

                    {{-- Swap button --}}
                    <div class="col-md-auto d-flex align-items-end pb-1">
                        <button type="button" id="swapBtn"
                                class="btn btn-outline-primary rounded-circle px-3"
                                title="Swap airports">⇄</button>
                    </div>

                    {{-- Arrival --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <span class="text-danger">✈</span> Arrival
                        </label>
                        <select name="arrival_code" class="form-select" required>
                            <option value="">-- Arrival Airport --</option>
                            @php $currentCountry = null; @endphp
                            @foreach($airports as $airport)
                                @if($currentCountry !== $airport->country)
                                    @if($currentCountry !== null)</optgroup>@endif
                                    <optgroup label="{{ $airport->country }}">
                                    @php $currentCountry = $airport->country; @endphp
                                @endif
                                <option value="{{ $airport->code }}">
                                    {{ $airport->city }} ({{ $airport->code }}) – {{ $airport->name }}
                                </option>
                            @endforeach
                            </optgroup>
                        </select>
                    </div>

                    {{-- Departure date --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">📅 Departure Date</label>
                        <input type="date" name="departure_date" class="form-control"
                               placeholder="Any date">
                    </div>

                    {{-- Return date --}}
                    <div class="col-md-2" id="returnDateWrapper">
                        <label class="form-label fw-semibold">📅 Return Date</label>
                        <input type="date" name="return_date" class="form-control"
                               placeholder="Any date">
                    </div>

                </div>

                <div class="row g-3 mt-1 align-items-end">

                    {{-- Passengers --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">👤 Passengers</label>
                        <select name="passengers" class="form-select">
                            @for($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}">{{ $i }} passenger{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Class --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">💺 Class</label>
                        <select name="class" class="form-select">
                            <option value="economique">Economy</option>
                            <option value="eco_premium">Premium Economy</option>
                            <option value="affaires">Business</option>
                            <option value="premiere">First Class</option>
                        </select>
                    </div>

                    {{-- Options --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Options</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="with_baggage" value="1" id="withBaggage">
                                <label class="form-check-label" for="withBaggage">With baggage</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="direct_only" value="1" id="directOnly">
                                <label class="form-check-label" for="directOnly">Direct only</label>
                            </div>
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100 search-btn">
                            🔍 Search Flights
                        </button>
                    </div>

                </div>
            </form>
        </div>

        {{-- Popular routes --}}
        <div class="mt-5">
            <h5 class="text-white mb-3">Popular routes from Algiers</h5>
            <div class="row g-2">
                @foreach([
                    ['ALG','CDG','Paris'],
                    ['ALG','IST','Istanbul'],
                    ['ALG','BCN','Barcelona'],
                    ['ALG','FCO','Rome'],
                    ['ALG','LHR','London'],
                    ['ALG','DXB','Dubai'],
                ] as $route)
                <div class="col-md-2 col-4">
                    <a href="{{ route('flights.search', [
                        'departure_code' => $route[0],
                        'arrival_code'   => $route[1],
                        'departure_date' => date('Y-m-d', strtotime('+7 days')),
                        'passengers'     => 1,
                        'class'          => 'economique',
                        'type'           => 'aller_simple'
                    ]) }}" class="popular-route-card text-decoration-none">
                        <div class="text-white fw-bold">{{ $route[0] }} → {{ $route[1] }}</div>
                        <div class="text-white opacity-75 small">{{ $route[2] }}</div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
.flights-hero {
    background: linear-gradient(135deg, #0a2342 0%, #1a4a8a 60%, #0d6efd 100%);
    min-height: 100vh;
    padding-top: 20px;
}
.flight-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.tab-btn {
    border: none;
    background: none;
    padding: 8px 20px;
    font-weight: 600;
    color: #666;
    border-bottom: 3px solid transparent;
    border-radius: 0;
}
.tab-btn.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: none;
}
.search-btn {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    border: none;
    border-radius: 10px;
    font-weight: 600;
}
.popular-route-card {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    padding: 12px;
    display: block;
    transition: 0.2s;
}
.popular-route-card:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const type = this.dataset.type;
        document.getElementById('flightType').value = type;
        const retour = document.getElementById('returnDateWrapper');
        if (type === 'aller_retour') {
            retour.style.display = '';
            retour.querySelector('input').required = true;
        } else {
            retour.style.display = 'none';
            retour.querySelector('input').required = false;
            retour.querySelector('input').value = '';
        }
    });
});

document.getElementById('swapBtn').addEventListener('click', function () {
    const dep = document.querySelector('[name="departure_code"]');
    const arr = document.querySelector('[name="arrival_code"]');
    const tmp = dep.value;
    dep.value = arr.value;
    arr.value = tmp;
});
</script>
@endpush

@endsection
