@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">Admin Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Stats Row --}}
    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="admin-card text-center">
                <h6 class="text-muted">Total Trips</h6>
                <h2 class="fw-bold">{{ $trips }}</h2>
                <a href="{{ route('admin.trips.index') }}" class="btn btn-sm btn-premium mt-2">Manage</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card text-center">
                <h6 class="text-muted">Total Users</h6>
                <h2 class="fw-bold">{{ $users }}</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-premium mt-2">Manage</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card text-center">
                <h6 class="text-muted">Total Bookings</h6>
                <h2 class="fw-bold">{{ $bookings }}</h2>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-premium mt-2">Manage</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card text-center">
                <h6 class="text-muted">Revenue</h6>
                <h2 class="fw-bold">{{ number_format($revenue, 2) }} DA</h2>
            </div>
        </div>
    </div>

    {{-- Recent Bookings --}}
    <h4 class="mb-3">Recent Bookings</h4>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Trip</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->user->name ?? 'Deleted User' }}</td>
                    <td>{{ $booking->trip->title ?? 'Deleted Trip' }}</td>
                    <td>{{ number_format($booking->total_price, 2) }} DA</td>
                    <td>
                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No bookings yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
