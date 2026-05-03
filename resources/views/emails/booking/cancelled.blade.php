@extends('emails.layout', ['user' => $booking->user])

@section('content')

@php
    $trip = $booking->trip;
    $user = $booking->user;
@endphp

<p class="greeting">Hello, {{ $user->name }}</p>
<p class="intro">Your booking has been cancelled. We're sorry to see you go — we hope to welcome you back soon.</p>

<div class="alert-box alert-danger">
    ❌ <strong>Booking Cancelled</strong> — Reference <strong>#{{ $booking->id }}</strong>
</div>

<div class="card">
    <div class="card-title">Cancelled Booking Details</div>
    <div class="detail-row">
        <span class="detail-label">Trip</span>
        <span class="detail-value">{{ $trip->title }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Destination</span>
        <span class="detail-value">{{ $trip->destination->name ?? 'N/A' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Planned Date</span>
        <span class="detail-value">{{ $trip->start_date->format('D, d M Y') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Passengers</span>
        <span class="detail-value">{{ $booking->number_of_persons }}</span>
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
    <a href="{{ url('/trips') }}" class="btn" style="background:#0a2342">Browse Other Trips</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#64748b;line-height:1.7">
    If you didn't request this cancellation or believe this is a mistake,
    please contact our support team immediately by replying to this email.
</p>

@endsection
