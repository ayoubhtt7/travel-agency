@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Trips</h2>
        <a href="{{ route('admin.trips.create') }}" class="btn btn-primary">+ Add Trip</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Destination</th>
                    <th>Price</th>
                    <th>Seats</th>
                    <th>Dates</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trips as $trip)
                <tr>
                    <td>{{ $trip->id }}</td>
                    <td>{{ $trip->title }}</td>
                    <td>{{ $trip->destination->name ?? 'N/A' }}</td>
                    <td>{{ number_format($trip->price, 2) }} DA</td>
                    <td>
                        <span class="badge bg-{{ $trip->available_seats > 0 ? 'success' : 'danger' }}">
                            {{ $trip->available_seats }}
                        </span>
                    </td>
                    <td>{{ $trip->start_date?->format('d M Y') }} → {{ $trip->end_date?->format('d M Y') }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.trips.edit', $trip) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.trips.destroy', $trip) }}" method="POST"
                              onsubmit="return confirm('Delete this trip?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No trips found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
