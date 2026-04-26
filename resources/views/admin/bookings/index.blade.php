@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">All Bookings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Trip</th>
                    <th>Persons</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->user->name ?? 'Deleted User' }}</td>
                    <td>{{ $booking->trip->title ?? 'Deleted Trip' }}</td>
                    <td>{{ $booking->number_of_persons }}</td>
                    <td>{{ number_format($booking->total_price, 2) }} DA</td>
                    <td>
                        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="d-flex gap-1">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending"   {{ $booking->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </td>
                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                    <td>
                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
                              onsubmit="return confirm('Delete this booking?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No bookings found.</td>
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
