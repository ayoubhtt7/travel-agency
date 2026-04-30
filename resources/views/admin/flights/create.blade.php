@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 800px;">

    <h2 class="mb-4">✈ Add New Flight</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.flights.store') }}" method="POST">
        @csrf

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Departure Airport</label>
                <select name="departure_airport_id"
                        class="form-select @error('departure_airport_id') is-invalid @enderror" required>
                    <option value="">-- Select --</option>
                    @php $currentCountry = null; @endphp
                    @foreach($airports as $airport)
                        @if($currentCountry !== $airport->country)
                            @if($currentCountry !== null)</optgroup>@endif
                            <optgroup label="{{ $airport->country }}">
                            @php $currentCountry = $airport->country; @endphp
                        @endif
                        <option value="{{ $airport->id }}"
                            {{ old('departure_airport_id') == $airport->id ? 'selected' : '' }}>
                            {{ $airport->city }} ({{ $airport->code }}) – {{ $airport->name }}
                        </option>
                    @endforeach
                    </optgroup>
                </select>
                @error('departure_airport_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Arrival Airport</label>
                <select name="arrival_airport_id"
                        class="form-select @error('arrival_airport_id') is-invalid @enderror" required>
                    <option value="">-- Select --</option>
                    @php $currentCountry = null; @endphp
                    @foreach($airports as $airport)
                        @if($currentCountry !== $airport->country)
                            @if($currentCountry !== null)</optgroup>@endif
                            <optgroup label="{{ $airport->country }}">
                            @php $currentCountry = $airport->country; @endphp
                        @endif
                        <option value="{{ $airport->id }}"
                            {{ old('arrival_airport_id') == $airport->id ? 'selected' : '' }}>
                            {{ $airport->city }} ({{ $airport->code }}) – {{ $airport->name }}
                        </option>
                    @endforeach
                    </optgroup>
                </select>
                @error('arrival_airport_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Airline</label>
                <input type="text" name="airline"
                       class="form-control @error('airline') is-invalid @enderror"
                       value="{{ old('airline') }}" placeholder="e.g. Air Algerie" required>
                @error('airline')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Flight Number</label>
                <input type="text" name="flight_number"
                       class="form-control @error('flight_number') is-invalid @enderror"
                       value="{{ old('flight_number') }}" placeholder="e.g. AH1024" required>
                @error('flight_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Departure Date & Time</label>
                <input type="datetime-local" name="departure_at"
                       class="form-control @error('departure_at') is-invalid @enderror"
                       value="{{ old('departure_at') }}" required>
                @error('departure_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Arrival Date & Time</label>
                <input type="datetime-local" name="arrival_at"
                       class="form-control @error('arrival_at') is-invalid @enderror"
                       value="{{ old('arrival_at') }}" required>
                @error('arrival_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Flight Type</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="aller_simple" {{ old('type') == 'aller_simple' ? 'selected' : '' }}>One Way</option>
                    <option value="aller_retour" {{ old('type') == 'aller_retour' ? 'selected' : '' }}>Round Trip</option>
                    <option value="direct"       {{ old('type') == 'direct'       ? 'selected' : '' }}>Direct</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Class</label>
                <select name="class" class="form-select @error('class') is-invalid @enderror" required>
                    <option value="economique"  {{ old('class') == 'economique'  ? 'selected' : '' }}>Economy</option>
                    <option value="eco_premium" {{ old('class') == 'eco_premium' ? 'selected' : '' }}>Premium Economy</option>
                    <option value="affaires"    {{ old('class') == 'affaires'    ? 'selected' : '' }}>Business</option>
                    <option value="premiere"    {{ old('class') == 'premiere'    ? 'selected' : '' }}>First Class</option>
                </select>
                @error('class')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Available Seats</label>
                <input type="number" name="available_seats"
                       class="form-control @error('available_seats') is-invalid @enderror"
                       value="{{ old('available_seats') }}" min="1" required>
                @error('available_seats')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Price (DA)</label>
                <input type="number" name="price"
                       class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price') }}" min="0" step="0.01" required>
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="with_baggage"
                           value="1" id="withBaggage" {{ old('with_baggage') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="withBaggage">
                        🧳 Baggage Included
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_direct"
                           value="1" id="isDirect" {{ old('is_direct', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="isDirect">
                        ✈ Direct Flight (no stopover)
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Create Flight</button>
            <a href="{{ route('admin.flights.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
