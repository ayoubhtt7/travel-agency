@extends('emails.layout', ['user' => $booking->user])

@section('content')

@php
    $flight       = $booking->flight;
    $returnFlight = $booking->returnFlight;
    $user         = $booking->user;
    $classMap     = ['economique' => 'Economy', 'eco_premium' => 'Premium Economy', 'affaires' => 'Business', 'premiere' => 'First Class'];
@endphp

<p class="greeting">Hello, {{ $user->name }} ✈️</p>
<p class="intro">Your flight has been booked successfully. Here are your flight details.</p>

<div class="alert-box alert-success">
    ✅ <strong>Flight Booked</strong> — Booking Reference <strong>#{{ $booking->id }}</strong>
</div>

{{-- Outbound Flight --}}
<div class="card">
    <div class="card-title">{{ $returnFlight ? 'Outbound Flight' : 'Flight Details' }}</div>
    <div class="detail-row">
        <span class="detail-label">Flight</span>
        <span class="detail-value">{{ $flight->airline }} · {{ $flight->flight_number }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">From</span>
        <span class="detail-value">{{ $flight->departureAirport->city }} ({{ $flight->departureAirport->code }})</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">To</span>
        <span class="detail-value">{{ $flight->arrivalAirport->city }} ({{ $flight->arrivalAirport->code }})</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Departure</span>
        <span class="detail-value">{{ $flight->departure_at->format('D, d M Y — H:i') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Arrival</span>
        <span class="detail-value">{{ $flight->arrival_at->format('D, d M Y — H:i') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Duration</span>
        <span class="detail-value">{{ $flight->duration }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Class</span>
        <span class="detail-value">{{ $classMap[$booking->class] ?? $booking->class }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Baggage</span>
        <span class="detail-value">{{ $flight->with_baggage ? '🧳 Included' : '🚫 Not included' }}</span>
    </div>
</div>

{{-- Return Flight --}}
@if($returnFlight)
<div class="card">
    <div class="card-title">Return Flight</div>
    <div class="detail-row">
        <span class="detail-label">Flight</span>
        <span class="detail-value">{{ $returnFlight->airline }} · {{ $returnFlight->flight_number }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">From</span>
        <span class="detail-value">{{ $returnFlight->departureAirport->city }} ({{ $returnFlight->departureAirport->code }})</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">To</span>
        <span class="detail-value">{{ $returnFlight->arrivalAirport->city }} ({{ $returnFlight->arrivalAirport->code }})</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Departure</span>
        <span class="detail-value">{{ $returnFlight->departure_at->format('D, d M Y — H:i') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Arrival</span>
        <span class="detail-value">{{ $returnFlight->arrival_at->format('D, d M Y — H:i') }}</span>
    </div>
</div>
@endif

{{-- Passengers --}}
<div class="card">
    <div class="card-title">Passengers ({{ $booking->passengers }})</div>
    @foreach($booking->passengerDetails as $i => $p)
    <div class="detail-row">
        <span class="detail-label">{{ $i + 1 }}. {{ ucfirst($p->type) }}</span>
        <span class="detail-value">{{ $p->full_name }} · {{ $p->passport_number }}</span>
    </div>
    @endforeach
    <hr style="border:none;border-top:1px solid #e2e8f0;margin:12px 0">
    <div class="total-row">
        <span class="total-label">Total Paid</span>
        <span class="total-value">{{ number_format($booking->total_price, 2) }} DA</span>
    </div>
</div>

<div style="text-align:center;margin-bottom:8px">
    <a href="{{ url('/dashboard') }}" class="btn">Go to Dashboard</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#64748b;line-height:1.7">
    Please arrive at the airport at least 2 hours before your departure.
    Carry a printed or digital copy of this confirmation.
</p>

@endsection
