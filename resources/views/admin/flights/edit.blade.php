@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 800px;">

    <h2 class="mb-4">✈ Edit Flight — {{ $flight->airline }} {{ $flight->flight_number }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.flights.update', $flight) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Airports --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Departure Airport</label>
                <select name="departure_airport_id" class="form-select" required>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}"
                            {{ old('departure_airport_id', $flight->departure_airport_id) == $airport->id ? 'selected' : '' }}>
                            {{ $airport->city }} ({{ $airport->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Arrival Airport</label>
                <select name="arrival_airport_id" class="form-select" required>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}"
                            {{ old('arrival_airport_id', $flight->arrival_airport_id) == $airport->id ? 'selected' : '' }}>
                            {{ $airport->city }} ({{ $airport->code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Airline --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Airline</label>
                <input type="text" name="airline" class="form-control"
                       value="{{ old('airline', $flight->airline) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Flight Number</label>
                <input type="text" name="flight_number" class="form-control"
                       value="{{ old('flight_number', $flight->flight_number) }}" required>
            </div>
        </div>

        {{-- Dates --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Departure</label>
                <input type="datetime-local" name="departure_at" class="form-control"
                       value="{{ old('departure_at', $flight->departure_at->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Arrival</label>
                <input type="datetime-local" name="arrival_at" class="form-control"
                       value="{{ old('arrival_at', $flight->arrival_at->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>

        {{-- Type + Class --}}
        <div class="row g-3 mb-3">

            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="oneway" {{ old('type', $flight->type) == 'oneway' ? 'selected' : '' }}>One Way</option>
                    <option value="roundtrip" {{ old('type', $flight->type) == 'roundtrip' ? 'selected' : '' }}>Round Trip</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Class</label>
                <select name="class" class="form-select" required>
                    <option value="economy" {{ old('class', $flight->class) == 'economy' ? 'selected' : '' }}>Economy</option>
                    <option value="business" {{ old('class', $flight->class) == 'business' ? 'selected' : '' }}>Business</option>
                    <option value="first" {{ old('class', $flight->class) == 'first' ? 'selected' : '' }}>First</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Seats</label>
                <input type="number" name="available_seats" class="form-control"
                       value="{{ old('available_seats', $flight->available_seats) }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control"
                       value="{{ old('price', $flight->price) }}" required>
            </div>

        </div>

        {{-- Options --}}
        <div class="mb-4">
            <label class="me-3">
                <input type="checkbox" name="with_baggage" value="1"
                    {{ old('with_baggage', $flight->with_baggage) ? 'checked' : '' }}>
                Baggage
            </label>

            <label>
                <input type="checkbox" name="is_direct" value="1"
                    {{ old('is_direct', $flight->is_direct) ? 'checked' : '' }}>
                Direct Flight
            </label>
        </div>

        <button class="btn btn-warning">Update Flight</button>

    </form>
</div>
@endsection