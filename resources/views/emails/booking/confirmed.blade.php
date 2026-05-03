@extends('emails.layout', ['user' => $booking->user])

@section('content')

@php
    $trip = $booking->trip;
    $user = $booking->user;
@endphp

<p class="greeting">Hello, {{ $user->name }} 👋</p>
<p class="intro">Great news! Your trip booking has been confirmed. Here's everything you need to know.</p>

<div class="alert-box alert-success">
    ✅ <strong>Booking Confirmed</strong> — Reference <strong>#{{ $booking->id }}</strong>
</div>

<div class="card">
    <div class="card-title">Trip Details</div>
    <div class="detail-row">
        <span class="detail-label">Trip</span>
        <span class="detail-value">{{ $trip->title }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Destination</span>
        <span class="detail-value">{{ $trip->destination->name ?? 'N/A' }}, {{ $trip->destination->country ?? '' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Start Date</span>
        <span class="detail-value">{{ $trip->start_date->format('D, d M Y') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">End Date</span>
        <span class="detail-value">{{ $trip->end_date->format('D, d M Y') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Duration</span>
        <span class="detail-value">{{ $trip->duration }} days</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Passengers</span>
        <span class="detail-value">{{ $booking->number_of_persons }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value"><span class="badge badge-success">Confirmed</span></span>
    </div>
    <hr style="border:none;border-top:1px solid #e2e8f0;margin:12px 0">
    <div class="total-row">
        <span class="total-label">Total Paid</span>
        <span class="total-value">{{ number_format($booking->total_price, 2) }} DA</span>
    </div>
</div>

<div style="text-align:center;margin-bottom:8px">
    <a href="{{ url('/bookings') }}" class="btn">View My Bookings</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#64748b;line-height:1.7">
    Need to cancel or make changes? You can manage your booking from your dashboard up to 24 hours before departure.
    If you need help, reply to this email.
</p>

@endsection
