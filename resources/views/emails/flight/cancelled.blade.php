@extends('emails.layout', ['user' => $booking->user])

@section('content')

@php
    $flight = $booking->flight;
    $user   = $booking->user;
@endphp

<p class="greeting">Hello, {{ $user->name }}</p>
<p class="intro">Your flight booking has been cancelled. We hope to see you on board soon.</p>

<div class="alert-box alert-danger">
    ❌ <strong>Flight Booking Cancelled</strong> — Reference <strong>#{{ $booking->id }}</strong>
</div>

<div class="card">
    <div class="card-title">Cancelled Flight Details</div>
    <div class="detail-row">
        <span class="detail-label">Flight</span>
        <span class="detail-value">{{ $flight->airline }} · {{ $flight->flight_number }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Route</span>
        <span class="detail-value">
            {{ $flight->departureAirport->city }} ({{ $flight->departureAirport->code }})
            → {{ $flight->arrivalAirport->city }} ({{ $flight->arrivalAirport->code }})
        </span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Departure</span>
        <span class="detail-value">{{ $flight->departure_at->format('D, d M Y — H:i') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Passengers</span>
        <span class="detail-value">{{ $booking->passengers }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value"><span class="badge badge-danger">Cancelled</span></span>
    </div>
    <hr style="border:none;border-top:1px solid #e2e8f0;margin:12px 0">
    <div class="total-row">
        <span class="total-label">Amount</span>
        <span class="total-value" style="color:#dc2626">{{ number_format($booking->total_price, 2) }} DA</span>
    </div>
</div>

<div style="text-align:center;margin-bottom:8px">
    <a href="{{ url('/flights') }}" class="btn" style="background:#0a2342">Search Flights Again</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#64748b;line-height:1.7">
    If you didn't request this cancellation, please contact us immediately by replying to this email.
</p>

@endsection
