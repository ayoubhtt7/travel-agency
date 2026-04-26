@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">My Dashboard</h2>
    <a href="{{ route('trips.index') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-globe2 me-1"></i> Browse Trips
    </a>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-5">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="mb-2 text-primary fs-2"><i class="bi bi-calendar-check"></i></div>
                <h6 class="text-muted mb-1">My Bookings</h6>
                <h2 class="fw-bold mb-0">{{ $totalTrips }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="mb-2 text-success fs-2"><i class="bi bi-cash-coin"></i></div>
                <h6 class="text-muted mb-1">Total Spent</h6>
                <h2 class="fw-bold mb-0">{{ number_format($totalSpent, 2) }} DA</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="mb-2 text-info fs-2"><i class="bi bi-person-circle"></i></div>
                <h6 class="text-muted mb-1">Account</h6>
                <h2 class="fw-bold mb-0 fs-4">{{ auth()->user()->name }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- Recent Bookings --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">My Bookings</h4>
</div>

@if($myBookings->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        You have no bookings yet. <a href="{{ route('trips.index') }}">Browse trips</a>.
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Trip</th>
                        <th>Persons</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myBookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->trip->title ?? 'N/A' }}</td>
                        <td>{{ $booking->number_of_persons }}</td>
                        <td>{{ number_format($booking->total_price, 2) }} DA</td>
                        <td>
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>{{ $booking->created_at->format('d M Y') }}</td>
                        <td>
                            @if($booking->status !== 'cancelled')
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                  onsubmit="return confirm('Cancel this booking?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
