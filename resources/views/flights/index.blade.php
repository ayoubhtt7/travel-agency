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
                        <label class="form-label fw-semibold">✈ Departure</label>
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

                    {{-- Swap --}}
                    <div class="col-md-auto d-flex align-items-end pb-1">
                        <button type="button" id="swapBtn"
                                class="btn btn-outline-primary rounded-circle px-3">⇄</button>
                    </div>

                    {{-- Arrival --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">✈ Arrival</label>
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

                    {{-- Departure date (OPTIONAL) --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">📅 Departure Date</label>
                        <input type="date" name="departure_date"
                               class="form-control"
                               min="{{ date('Y-m-d') }}">
                    </div>

                    {{-- Return date --}}
                    <div class="col-md-2" id="returnDateWrapper">
                        <label class="form-label fw-semibold">📅 Return Date</label>
                        <input type="date" name="return_date"
                               class="form-control"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}">
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
                                       name="with_baggage" value="1">
                                <label class="form-check-label">With baggage</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="direct_only" value="1">
                                <label class="form-check-label">Direct only</label>
                            </div>
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            🔍 Search Flights
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>

{{-- STYLES --}}
<style>
.flights-hero {
    background: linear-gradient(135deg, #0a2342, #0d6efd);
    min-height: 100vh;
}
.flight-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
}
.tab-btn {
    border: none;
    background: none;
    padding: 8px 20px;
    font-weight: 600;
}
.tab-btn.active {
    color: #0d6efd;
    border-bottom: 3px solid #0d6efd;
}
</style>

{{-- SCRIPTS --}}
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
        } else {
            retour.style.display = 'none';
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

@endsection