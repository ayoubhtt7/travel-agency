@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 860px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🎫 Booking Details #{{ $flightBooking->id }}</h2>
        <a href="{{ route('admin.flight-bookings.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">

        {{-- Customer --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">👤 Customer</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $flightBooking->user->name ?? 'Deleted' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $flightBooking->user->email ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Booked on:</strong> {{ $flightBooking->created_at->format('d M Y \a\t H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">📋 Summary</div>
                <div class="card-body">
                    @php $classMap = ['economique'=>'Economy','eco_premium'=>'Premium Economy','affaires'=>'Business','premiere'=>'First Class']; @endphp
                    <p class="mb-1"><strong>Type:</strong> {{ $flightBooking->type === 'aller_retour' ? 'Round Trip' : 'One Way' }}</p>
                    <p class="mb-1"><strong>Class:</strong> {{ $classMap[$flightBooking->class] ?? $flightBooking->class }}</p>
                    <p class="mb-1"><strong>Passengers:</strong> {{ $flightBooking->passengers }}</p>
                    <p class="mb-1"><strong>Total:</strong>
                        <span class="fw-bold text-primary fs-5">{{ number_format($flightBooking->total_price, 2) }} DA</span>
                    </p>
                    <p class="mb-0"><strong>Status:</strong>
                        <span class="badge bg-{{ $flightBooking->status === 'confirmed' ? 'success' : ($flightBooking->status === 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($flightBooking->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Outbound flight --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">✈ Outbound Flight</div>
                <div class="card-body">
                    @if($flightBooking->flight)
                    <div class="row text-center">
                        <div class="col">
                            <div class="fs-3 fw-bold">{{ $flightBooking->flight->departure_at->format('H:i') }}</div>
                            <div class="fw-bold fs-5">{{ $flightBooking->flight->departureAirport->code }}</div>
                            <div class="text-muted">{{ $flightBooking->flight->departureAirport->city }}</div>
                            <div class="text-muted small">{{ $flightBooking->flight->departure_at->format('d M Y') }}</div>
                        </div>
                        <div class="col d-flex flex-column align-items-center justify-content-center">
                            <div class="text-muted small mb-1">{{ $flightBooking->flight->duration }}</div>
                            <div class="text-primary fs-5">──── ✈ ────</div>
                            <div class="text-muted small mt-1">{{ $flightBooking->flight->airline }} · {{ $flightBooking->flight->flight_number }}</div>
                            @if($flightBooking->flight->is_direct)
                                <span class="badge bg-success mt-1">Direct</span>
                            @else
                                <span class="badge bg-warning text-dark mt-1">Stopover</span>
                            @endif
                        </div>
                        <div class="col">
                            <div class="fs-3 fw-bold">{{ $flightBooking->flight->arrival_at->format('H:i') }}</div>
                            <div class="fw-bold fs-5">{{ $flightBooking->flight->arrivalAirport->code }}</div>
                            <div class="text-muted">{{ $flightBooking->flight->arrivalAirport->city }}</div>
                            <div class="text-muted small">{{ $flightBooking->flight->arrival_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    @else
                        <p class="text-muted mb-0">Flight has been deleted.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Return flight --}}
        @if($flightBooking->returnFlight)
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">↩ Return Flight</div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <div class="fs-3 fw-bold">{{ $flightBooking->returnFlight->departure_at->format('H:i') }}</div>
                            <div class="fw-bold fs-5">{{ $flightBooking->returnFlight->departureAirport->code }}</div>
                            <div class="text-muted">{{ $flightBooking->returnFlight->departureAirport->city }}</div>
                            <div class="text-muted small">{{ $flightBooking->returnFlight->departure_at->format('d M Y') }}</div>
                        </div>
                        <div class="col d-flex flex-column align-items-center justify-content-center">
                            <div class="text-muted small mb-1">{{ $flightBooking->returnFlight->duration }}</div>
                            <div class="text-primary fs-5">──── ✈ ────</div>
                            <div class="text-muted small mt-1">{{ $flightBooking->returnFlight->airline }} · {{ $flightBooking->returnFlight->flight_number }}</div>
                        </div>
                        <div class="col">
                            <div class="fs-3 fw-bold">{{ $flightBooking->returnFlight->arrival_at->format('H:i') }}</div>
                            <div class="fw-bold fs-5">{{ $flightBooking->returnFlight->arrivalAirport->code }}</div>
                            <div class="text-muted">{{ $flightBooking->returnFlight->arrivalAirport->city }}</div>
                            <div class="text-muted small">{{ $flightBooking->returnFlight->arrival_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Passengers --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    👥 Passengers ({{ $flightBooking->passengerDetails->count() }})
                </div>
                <div class="card-body p-0">
                    @if($flightBooking->passengerDetails->isEmpty())
                        <p class="text-muted p-3 mb-0">No passenger details recorded.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Type</th>
                                    <th>Gender</th>
                                    <th>Date of Birth</th>
                                    <th>Nationality</th>
                                    <th>Passport No.</th>
                                    <th>Passport Expiry</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($flightBooking->passengerDetails as $i => $passenger)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <strong>{{ $passenger->full_name }}</strong>
                                        @if($i === 0)
                                            <span class="badge bg-primary ms-1">Lead</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($passenger->type) }}</span></td>
                                    <td>{{ ucfirst($passenger->gender) }}</td>
                                    <td>{{ $passenger->date_of_birth->format('d M Y') }}</td>
                                    <td>{{ $passenger->nationality }}</td>
                                    <td><code>{{ $passenger->passport_number }}</code></td>
                                    <td>
                                        @php $expiry = $passenger->passport_expiry; @endphp
                                        <span class="{{ $expiry->isPast() ? 'text-danger fw-bold' : ($expiry->diffInMonths(now()) < 6 ? 'text-warning fw-bold' : '') }}">
                                            {{ $expiry->format('d M Y') }}
                                        </span>
                                        @if($expiry->isPast())
                                            <span class="badge bg-danger ms-1">Expired</span>
                                        @elseif($expiry->diffInMonths(now()) < 6)
                                            <span class="badge bg-warning text-dark ms-1">Expiring soon</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Change status --}}
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header fw-bold">⚙ Change Status</div>
                <div class="card-body">
                    <form action="{{ route('admin.flight-bookings.update', $flightBooking) }}"
                          method="POST" class="d-flex gap-2 align-items-center">
                        @csrf @method('PUT')
                        <select name="status" class="form-select" style="max-width: 200px;">
                            <option value="pending"   {{ $flightBooking->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $flightBooking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $flightBooking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button class="btn btn-warning">Save</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
