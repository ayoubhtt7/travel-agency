@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">My Bookings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->isEmpty())
        <div class="alert alert-info">
            You have no bookings yet. <a href="{{ route('trips.index') }}">Browse trips</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Trip</th>
                        <th>Destination</th>
                        <th>Persons</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Booked On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('trips.show', $booking->trip_id) }}">
                                {{ $booking->trip->title ?? 'N/A' }}
                            </a>
                        </td>
                        <td>{{ $booking->trip->destination->name ?? 'N/A' }}</td>
                        <td>{{ $booking->number_of_persons }}</td>
                        <td>{{ number_format($booking->total_price, 2) }} DA</td>
                        <td>
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>{{ $booking->created_at->format('d M Y') }}</td>
                        <td>
                            @if($booking->status === 'confirmed')
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Cancel</button>
                            </form>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

@endsection
