@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 860px;">

    {{-- Flight summary header --}}
    <div class="booking-header mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h2 class="mb-1">Passenger Details</h2>
                <p class="text-muted mb-0">Fill in details for all {{ $passengerCount }} passenger(s) below.</p>
            </div>
            <div class="text-end">
                <div class="text-muted small">Total Price</div>
                <div class="fs-3 fw-bold text-primary">{{ number_format($totalPrice, 2) }} DA</div>
            </div>
        </div>

        {{-- Flight info strip --}}
        <div class="flight-strip mt-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">

                <div class="text-center">
                    <div class="fs-5 fw-bold">{{ $flight->departureAirport->code }}</div>
                    <div class="text-muted small">{{ $flight->departure_at->format('H:i') }}</div>
                </div>

                <div class="flex-grow-1 text-center text-muted small">
                    ──── ✈ {{ $flight->airline }} · {{ $flight->flight_number }} ────
                    <div>{{ $flight->departure_at->format('d M Y') }}</div>
                </div>

                <div class="text-center">
                    <div class="fs-5 fw-bold">{{ $flight->arrivalAirport->code }}</div>
                    <div class="text-muted small">{{ $flight->arrival_at->format('H:i') }}</div>
                </div>

                @if($returnFlight)
                <div class="vr mx-2"></div>

                <div class="text-center">
                    <div class="fs-5 fw-bold">{{ $returnFlight->departureAirport->code }}</div>
                    <div class="text-muted small">{{ $returnFlight->departure_at->format('H:i') }}</div>
                </div>

                <div class="flex-grow-1 text-center text-muted small">
                    ──── ↩ {{ $returnFlight->airline }} · {{ $returnFlight->flight_number }} ────
                    <div>{{ $returnFlight->departure_at->format('d M Y') }}</div>
                </div>

                <div class="text-center">
                    <div class="fs-5 fw-bold">{{ $returnFlight->arrivalAirport->code }}</div>
                    <div class="text-muted small">{{ $returnFlight->arrival_at->format('H:i') }}</div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('flights.book') }}" method="POST">
        @csrf

        <input type="hidden" name="flight_id" value="{{ $flight->id }}">
        <input type="hidden" name="return_flight_id" value="{{ $returnFlight?->id }}">
        <input type="hidden" name="passengers" value="{{ $passengerCount }}">

        {{-- ✅ FIXED ENUM VALUES --}}
        <input type="hidden" name="class" value="{{ in_array($request->class, ['economy','business','first']) ? $request->class : 'economy' }}">
        <input type="hidden" name="type" value="{{ in_array($request->type, ['oneway','roundtrip','direct']) ? $request->type : 'oneway' }}">

        {{-- PASSENGERS --}}
        @for($i = 0; $i < $passengerCount; $i++)
        <div class="passenger-card mb-4">
            <div class="passenger-card-header">
                <span class="passenger-number">{{ $i + 1 }}</span>
                <span>Passenger {{ $i + 1 }}</span>
                @if($i === 0)
                    <span class="badge bg-primary ms-2">Lead Passenger</span>
                @endif
            </div>

            <div class="passenger-card-body">

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="passenger[{{ $i }}][first_name]" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="passenger[{{ $i }}][last_name]" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Passenger Type</label>
                        <select name="passenger[{{ $i }}][type]" class="form-select" required>
                            <option value="adult">Adult (12+)</option>
                            <option value="child">Child (2–11)</option>
                            <option value="infant">Infant (under 2)</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" name="passenger[{{ $i }}][date_of_birth]" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Gender</label>
                        <select name="passenger[{{ $i }}][gender]" class="form-select" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nationality</label>
                        <input type="text" name="passenger[{{ $i }}][nationality]" class="form-control" required>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Passport Number</label>
                        <input type="text" name="passenger[{{ $i }}][passport_number]" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Passport Expiry</label>
                        <input type="date" name="passenger[{{ $i }}][passport_expiry]" class="form-control" required>
                    </div>
                </div>

            </div>
        </div>
        @endfor

        {{-- Confirm --}}
        <div class="confirm-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="text-muted small mb-1">
                        {{ $passengerCount }} passenger(s) ·
                        {{ strtolower($request->class) }} ·
                        {{ strtolower($request->type) }}
                    </div>
                    <div class="fw-bold fs-5">
                        Total: <span class="text-primary">{{ number_format($totalPrice, 2) }} DA</span>
                    </div>
                </div>
                <div class="mb-3">
                <label class="form-label fw-semibold">Payment Method</label>

                <select name="payment_method" class="form-select" required>
                    <option value="">Choose payment method</option>
                    <option value="Credit Card">💳 Credit Card</option>
                    <option value="PayPal">🅿️ PayPal</option>
                    <option value="BaridiMob">📱 BaridiMob</option>
                    <option value="Cash">💵 Cash</option>
                </select>
            </div>
                <div class="col-md-4 text-end mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        Confirm Booking
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection