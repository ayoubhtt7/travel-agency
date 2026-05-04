@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">🚗 Car Bookings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Car</th>
                    <th>Pick-up</th>
                    <th>Return</th>
                    <th>Location</th>
                    <th>Days</th>
                    <th>Total</th>
                    <th>Trip booking</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>
                        <strong>{{ $booking->user->name ?? 'Deleted' }}</strong>
                        <div class="text-muted small">{{ $booking->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <strong>{{ $booking->car->brand ?? 'N/A' }} {{ $booking->car->model ?? '' }}</strong>
                        <div class="text-muted small">{{ ucfirst($booking->car->type ?? '') }}</div>
                    </td>
                    <td>{{ $booking->pickup_date->format('d M Y') }}</td>
                    <td>{{ $booking->return_date->format('d M Y') }}</td>
                    <td class="small">{{ $booking->pickup_location }}</td>
                    <td class="text-center">{{ $booking->days }}</td>
                    <td class="fw-bold">{{ number_format($booking->total_price, 2) }} DA</td>
                    <td>
                        @if($booking->tripBooking)
                            <a href="{{ route('admin.bookings.show', $booking->tripBooking) }}"
                               class="badge bg-info text-dark text-decoration-none">
                                Trip #{{ $booking->tripBooking->id }}
                            </a>
                        @else
                            <span class="text-muted small">Standalone</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.car-bookings.update', $booking) }}"
                              method="POST" class="d-flex gap-1">
                            @csrf @method('PUT')
                            <select name="status" class="form-select form-select-sm" style="width:120px;">
                                <option value="pending"   {{ $booking->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button class="btn btn-sm btn-primary">✓</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.car-bookings.destroy', $booking) }}"
                              method="POST" onsubmit="return confirm('Delete this booking?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted py-4">No car bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $bookings->links() }}
    </div>

</div>
@endsection
