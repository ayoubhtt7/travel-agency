@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 800px;">

    <h2 class="mb-4">
        ✈ {{ isset($flight) ? 'Edit Flight' : 'Add Flight' }}
    </h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">

                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach

            </ul>
        </div>
    @endif

    <form action="{{ isset($flight)
            ? route('admin.flights.update', $flight)
            : route('admin.flights.store') }}"
          method="POST">

        @csrf

        @if(isset($flight))
            @method('PUT')
        @endif

        {{-- Airports --}}
        <div class="row g-3 mb-3">

            {{-- Departure Airport --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Departure Airport
                </label>

                <select name="departure_airport_id"
                        class="form-select"
                        required>

                    @foreach($airports as $airport)

                        <option value="{{ $airport->id }}"
                            {{ old('departure_airport_id', $flight->departure_airport_id ?? '') == $airport->id ? 'selected' : '' }}>

                            {{ $airport->city }}
                            ({{ $airport->code }})

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Arrival Airport --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Arrival Airport
                </label>

                <select name="arrival_airport_id"
                        class="form-select"
                        required>

                    @foreach($airports as $airport)

                        <option value="{{ $airport->id }}"
                            {{ old('arrival_airport_id', $flight->arrival_airport_id ?? '') == $airport->id ? 'selected' : '' }}>

                            {{ $airport->city }}
                            ({{ $airport->code }})

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        {{-- Airline + Flight Number --}}
        <div class="row g-3 mb-3">

            <div class="col-md-6">

                <label class="form-label">
                    Airline
                </label>

                <input type="text"
                       name="airline"
                       class="form-control"
                       value="{{ old('airline', $flight->airline ?? '') }}"
                       required>

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Flight Number
                </label>

                <input type="text"
                       name="flight_number"
                       class="form-control"
                       value="{{ old('flight_number', $flight->flight_number ?? '') }}"
                       required>

            </div>

        </div>

        {{-- Departure + Arrival --}}
        <div class="row g-3 mb-3">

            {{-- Departure --}}
            <div class="col-md-6">

                <label class="form-label">
                    Departure
                </label>

                <input type="datetime-local"
                       name="departure_at"
                       class="form-control"
                       value="{{ old(
                            'departure_at',
                            isset($flight)
                                ? \Carbon\Carbon::parse($flight->departure_at)->format('Y-m-d\TH:i')
                                : ''
                       ) }}"
                       required>

            </div>

            {{-- Arrival --}}
            <div class="col-md-6">

                <label class="form-label">
                    Arrival
                </label>

                <input type="datetime-local"
                       name="arrival_at"
                       class="form-control"
                       value="{{ old(
                            'arrival_at',
                            isset($flight)
                                ? \Carbon\Carbon::parse($flight->arrival_at)->format('Y-m-d\TH:i')
                                : ''
                       ) }}"
                       required>

            </div>

        </div>

        {{-- Type + Class + Seats + Price --}}
        <div class="row g-3 mb-3">

            {{-- Flight Type --}}
            <div class="col-md-3">

                <label class="form-label">
                    Flight Type
                </label>

                <select name="type"
                        id="flight-type"
                        class="form-select"
                        required>

                    <option value="oneway"
                        {{ old('type', $flight->type ?? '') == 'oneway' ? 'selected' : '' }}>
                        One Way
                    </option>

                    <option value="roundtrip"
                        {{ old('type', $flight->type ?? '') == 'roundtrip' ? 'selected' : '' }}>
                        Round Trip
                    </option>

                </select>

            </div>

            {{-- Class --}}
            <div class="col-md-3">

                <label class="form-label">
                    Class
                </label>

                <select name="class"
                        class="form-select"
                        required>

                    <option value="economy"
                        {{ old('class', $flight->class ?? '') == 'economy' ? 'selected' : '' }}>
                        Economy
                    </option>

                    <option value="business"
                        {{ old('class', $flight->class ?? '') == 'business' ? 'selected' : '' }}>
                        Business
                    </option>

                    <option value="first"
                        {{ old('class', $flight->class ?? '') == 'first' ? 'selected' : '' }}>
                        First Class
                    </option>

                </select>

            </div>

            {{-- Seats --}}
            <div class="col-md-3">

                <label class="form-label">
                    Seats
                </label>

                <input type="number"
                       name="available_seats"
                       class="form-control"
                       value="{{ old('available_seats', $flight->available_seats ?? 0) }}"
                       required>

            </div>

            {{-- Price --}}
            <div class="col-md-3">

                <label class="form-label">
                    Price
                </label>

                <input type="number"
                       name="price"
                       class="form-control"
                       value="{{ old('price', $flight->price ?? 0) }}"
                       required>

            </div>

        </div>

        {{-- Return Dates --}}
        <div class="row g-3 mb-3"
             id="return-wrapper"
             style="display: none;">

            {{-- Return Departure --}}
            <div class="col-md-6">

                <label class="form-label">
                    Return Departure
                </label>

                <input type="datetime-local"
                       name="return_departure_at"
                       class="form-control"
                       value="{{ old(
                            'return_departure_at',
                            isset($flight) && $flight->return_departure_at
                                ? \Carbon\Carbon::parse($flight->return_departure_at)->format('Y-m-d\TH:i')
                                : ''
                       ) }}">

            </div>

            {{-- Return Arrival --}}
            <div class="col-md-6">

                <label class="form-label">
                    Return Arrival
                </label>

                <input type="datetime-local"
                       name="return_arrival_at"
                       class="form-control"
                       value="{{ old(
                            'return_arrival_at',
                            isset($flight) && $flight->return_arrival_at
                                ? \Carbon\Carbon::parse($flight->return_arrival_at)->format('Y-m-d\TH:i')
                                : ''
                       ) }}">

            </div>

        </div>

        {{-- Options --}}
        <div class="mb-4">

            {{-- Baggage --}}
            <div class="form-check mb-2">

                <input type="checkbox"
                       name="with_baggage"
                       value="1"
                       class="form-check-input"
                       id="with_baggage"
                    {{ old('with_baggage', $flight->with_baggage ?? false) ? 'checked' : '' }}>

                <label class="form-check-label"
                       for="with_baggage">

                    With baggage

                </label>

            </div>

            {{-- Direct --}}
            <div class="form-check">

                <input type="checkbox"
                       name="is_direct"
                       value="1"
                       class="form-check-input"
                       id="is_direct"
                    {{ old('is_direct', $flight->is_direct ?? false) ? 'checked' : '' }}>

                <label class="form-check-label"
                       for="is_direct">

                    Direct flight

                </label>

            </div>

        </div>

        {{-- Submit --}}
        <button class="btn btn-primary">

            {{ isset($flight) ? 'Update Flight' : 'Save Flight' }}

        </button>

    </form>

</div>

{{-- Return Date Toggle --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const flightType = document.getElementById('flight-type');

    const returnWrapper = document.getElementById('return-wrapper');

    function toggleReturnFields() {

        if (flightType.value === 'roundtrip') {

            returnWrapper.style.display = 'flex';

        } else {

            returnWrapper.style.display = 'none';

        }
    }

    toggleReturnFields();

    flightType.addEventListener('change', toggleReturnFields);

});

</script>

@endsection