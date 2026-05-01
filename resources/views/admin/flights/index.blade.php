@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>✈ Manage Flights</h2>
        <a href="{{ route('admin.flights.create') }}" class="btn btn-primary">+ Add Flight</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Flight</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Date</th>
                    <th>Class</th>
                    <th>Seats</th>
                    <th>Price</th>
                    <th>Options</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flights as $flight)
                <tr>
                    <td>{{ $flight->id }}</td>
                    <td>
                        <strong>{{ $flight->airline }}</strong>
                        <div class="text-muted small">{{ $flight->flight_number }}</div>
                    </td>
                    <td>
                        <strong>{{ $flight->departureAirport->code ?? 'N/A' }}</strong>
                        <div class="text-muted small">{{ $flight->departureAirport->city ?? '—' }}</div>
                    </td>
                    <td>
                        <strong>{{ $flight->arrivalAirport->code ?? 'N/A' }}</strong>
                        <div class="text-muted small">{{ $flight->arrivalAirport->city ?? '—' }}</div>
                    </td>
                    <td>
                        {{ $flight->departure_at->format('d M Y') }}
                        <div class="text-muted small">
                            {{ $flight->departure_at->format('H:i') }} → {{ $flight->arrival_at->format('H:i') }}
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ ucfirst(str_replace('_', ' ', $flight->class)) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $flight->available_seats > 0 ? 'success' : 'danger' }}">
                            {{ $flight->available_seats }}
                        </span>
                    </td>
                    <td>{{ number_format($flight->price, 2) }} DA</td>
                    <td>
                        @if($flight->with_baggage)
                            <span class="badge bg-info text-dark">🧳 Baggage</span>
                        @endif
                        @if($flight->is_direct)
                            <span class="badge bg-success">Direct</span>
                        @else
                            <span class="badge bg-warning text-dark">Stopover</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.flights.edit', $flight) }}"
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.flights.destroy', $flight) }}" method="POST"
                                  onsubmit="return confirm('Delete this flight?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        No flights found. <a href="{{ route('admin.flights.create') }}">Add the first flight</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $flights->links() }}
    </div>

</div>
@endsection