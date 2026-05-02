@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 800px;">

    <h2 class="mb-4">✈ Add / Edit Flight</h2>

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
        @if(isset($flight)) @method('PUT') @endif

        {{-- Airports --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Departure Airport</label>
                <select name="departure_airport_id" class="form-select" required>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}"
                            {{ old('departure_airport_id', $flight->departure_airport_id ?? '') == $airport->id ? 'selected' : '' }}>
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
                            {{ old('arrival_airport_id', $flight->arrival_airport_id ?? '') == $airport->id ? 'selected' : '' }}>
                            {{ $airport->city }} ({{ $airport->code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Airline --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label>Airline</label>
                <input type="text" name="airline" class="form-control"
                       value="{{ old('airline', $flight->airline ?? '') }}" required>
            </div>

            <div class="col-md-6">
                <label>Flight Number</label>
                <input type="text" name="flight_number" class="form-control"
                       value="{{ old('flight_number', $flight->flight_number ?? '') }}" required>
            </div>
        </div>

        {{-- Dates --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label>Departure</label>
                <input type="datetime-local" name="departure_at" class="form-control"
                       value="{{ old('departure_at', isset($flight) ? $flight->departure_at->format('Y-m-d\TH:i') : '') }}" required>
            </div>

            <div class="col-md-6">
                <label>Arrival</label>
                <input type="datetime-local" name="arrival_at" class="form-control"
                       value="{{ old('arrival_at', isset($flight) ? $flight->arrival_at->format('Y-m-d\TH:i') : '') }}" required>
            </div>
        </div>

        {{-- TYPE + CLASS (FIXED HERE) --}}
        <div class="row g-3 mb-3">

            {{-- ✅ TYPE --}}
            <div class="col-md-4">
                <label>Flight Type</label>
                <select name="type" class="form-select" required>
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

            {{-- ✅ CLASS --}}
            <div class="col-md-4">
                <label>Class</label>
                <select name="class" class="form-select" required>
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
            <div class="col-md-2">
                <label>Seats</label>
                <input type="number" name="available_seats" class="form-control"
                       value="{{ old('available_seats', $flight->available_seats ?? 0) }}" required>
            </div>

            {{-- Price --}}
            <div class="col-md-2">
                <label>Price</label>
                <input type="number" name="price" class="form-control"
                       value="{{ old('price', $flight->price ?? 0) }}" required>
            </div>
        </div>

        {{-- Options --}}
        <div class="mb-4">
            <div class="form-check">
                <input type="checkbox" name="with_baggage" value="1"
                    {{ old('with_baggage', $flight->with_baggage ?? false) ? 'checked' : '' }}>
                <label>With baggage</label>
            </div>

            <div class="form-check">
                <input type="checkbox" name="is_direct" value="1"
                    {{ old('is_direct', $flight->is_direct ?? false) ? 'checked' : '' }}>
                <label>Direct flight</label>
            </div>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection