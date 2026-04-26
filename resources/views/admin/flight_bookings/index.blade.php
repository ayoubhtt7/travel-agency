@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">🎫 Flight Bookings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Flight</th>
                    <th>Route</th>
                    <th>Date</th>
                    <th>Passengers</th>
                    <th>Class</th>
                    <th>Total</th>
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
                        <strong>{{ $booking->flight->airline ?? 'N/A' }}</strong>
                        <div class="text-muted small">{{ $booking->flight->flight_number ?? '' }}</div>
                    </td>
                    <td>
                        @if($booking->flight)
                            <span class="fw-bold">{{ $booking->flight->departureAirport->code }}</span>
                            <span class="text-muted mx-1">→</span>
                            <span class="fw-bold">{{ $booking->flight->arrivalAirport->code }}</span>
                            @if($booking->returnFlight)
                                <div class="text-muted small">
                                    ↩ {{ $booking->returnFlight->departureAirport->code }}
                                    → {{ $booking->returnFlight->arrivalAirport->code }}
                                </div>
                            @endif
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        {{ $booking->flight?->departure_at->format('d M Y') ?? 'N/A' }}
                        <div class="text-muted small">{{ $booking->flight?->departure_at->format('H:i') }}</div>
                    </td>
                    <td class="text-center">{{ $booking->passengers }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ ucfirst(str_replace('_', ' ', $booking->class)) }}
                        </span>
                    </td>
                    <td class="fw-bold">{{ number_format($booking->total_price, 2) }} DA</td>
                    <td>
                        <form action="{{ route('admin.flight-bookings.update', $booking) }}"
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
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.flight-bookings.show', $booking) }}"
                               class="btn btn-sm btn-info text-white">Details</a>
                            <form action="{{ route('admin.flight-bookings.destroy', $booking) }}"
                                  method="POST" onsubmit="return confirm('Delete this booking?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">No flight bookings found.</td>
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
