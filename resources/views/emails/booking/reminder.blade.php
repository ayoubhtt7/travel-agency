@extends('emails.layout', ['user' => $booking->user])

@section('content')

@php
    $trip = $booking->trip;
    $user = $booking->user;
@endphp

<p class="greeting">Hello, {{ $user->name }} 🌍</p>
<p class="intro">This is a friendly reminder that your trip is coming up in <strong>2 days</strong>. Time to get packing!</p>

<div class="alert-box alert-warning">
    ⏰ <strong>Departure in 2 days</strong> — {{ $trip->start_date->format('D, d M Y') }}
</div>

<div class="card">
    <div class="card-title">Your Upcoming Trip</div>
    <div class="detail-row">
        <span class="detail-label">Trip</span>
        <span class="detail-value">{{ $trip->title }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Destination</span>
        <span class="detail-value">{{ $trip->destination->name ?? 'N/A' }}, {{ $trip->destination->country ?? '' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Departure</span>
        <span class="detail-value">{{ $trip->start_date->format('D, d M Y') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Return</span>
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
        <span class="detail-label">Booking Ref</span>
        <span class="detail-value">#{{ $booking->id }}</span>
    </div>
</div>

{{-- Checklist --}}
<div class="card" style="background:#fffbeb;border-color:#fde68a">
    <div class="card-title" style="color:#92400e">✅ Pre-Departure Checklist</div>
    <p style="font-size:14px;color:#78350f;line-height:2">
        ☐ &nbsp; Check your passport validity<br>
        ☐ &nbsp; Confirm hotel/accommodation<br>
        ☐ &nbsp; Pack weather-appropriate clothing<br>
        ☐ &nbsp; Download offline maps<br>
        ☐ &nbsp; Notify your bank of travel<br>
        ☐ &nbsp; Arrange airport transport
    </p>
</div>

<div style="text-align:center;margin-bottom:8px">
    <a href="{{ url('/bookings') }}" class="btn">View Booking Details</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#64748b;line-height:1.7">
    Have a wonderful trip! If you have any questions before you depart, don't hesitate to reach out.
</p>

@endsection
