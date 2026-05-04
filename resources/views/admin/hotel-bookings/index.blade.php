@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">🏨 Hotel Bookings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Hotel</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Nights</th>
                    <th>Guests</th>
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
                        <strong>{{ $booking->hotel->name ?? 'N/A' }}</strong>
                        <div class="text-warning small">
                            @if($booking->hotel)
                                {{ str_repeat('★', $booking->hotel->stars) }}
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ ucfirst($booking->room->type ?? '—') }}</span>
                        @if($booking->room?->with_breakfast)
                            <span class="badge bg-success ms-1">🍳</span>
                        @endif
                    </td>
                    <td>{{ $booking->check_in->format('d M Y') }}</td>
                    <td>{{ $booking->check_out->format('d M Y') }}</td>
                    <td class="text-center">{{ $booking->nights }}</td>
                    <td class="text-center">{{ $booking->guests }}</td>
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
                        <form action="{{ route('admin.hotel-bookings.update', $booking) }}"
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
                            <a href="{{ route('admin.hotel-bookings.show', $booking) }}"
                               class="btn btn-sm btn-info text-white">Details</a>
                            <form action="{{ route('admin.hotel-bookings.destroy', $booking) }}"
                                  method="POST" onsubmit="return confirm('Delete this booking?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center text-muted py-4">No hotel bookings found.</td>
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
