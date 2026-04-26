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

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('flights.book') }}" method="POST">
        @csrf
        <input type="hidden" name="flight_id"        value="{{ $flight->id }}">
        <input type="hidden" name="return_flight_id" value="{{ $returnFlight?->id }}">
        <input type="hidden" name="passengers"       value="{{ $passengerCount }}">
        <input type="hidden" name="class"            value="{{ $request->class }}">
        <input type="hidden" name="type"             value="{{ $request->type }}">

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
                        <input type="text" name="passenger[{{ $i }}][first_name]"
                               class="form-control @error('passenger.'.$i.'.first_name') is-invalid @enderror"
                               value="{{ old('passenger.'.$i.'.first_name') }}"
                               placeholder="As on passport" required>
                        @error('passenger.'.$i.'.first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="passenger[{{ $i }}][last_name]"
                               class="form-control @error('passenger.'.$i.'.last_name') is-invalid @enderror"
                               value="{{ old('passenger.'.$i.'.last_name') }}"
                               placeholder="As on passport" required>
                        @error('passenger.'.$i.'.last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Passenger Type</label>
                        <select name="passenger[{{ $i }}][type]"
                                class="form-select @error('passenger.'.$i.'.type') is-invalid @enderror" required>
                            <option value="adult"  {{ old('passenger.'.$i.'.type', 'adult') === 'adult'  ? 'selected' : '' }}>Adult (12+)</option>
                            <option value="child"  {{ old('passenger.'.$i.'.type') === 'child'  ? 'selected' : '' }}>Child (2–11)</option>
                            <option value="infant" {{ old('passenger.'.$i.'.type') === 'infant' ? 'selected' : '' }}>Infant (under 2)</option>
                        </select>
                        @error('passenger.'.$i.'.type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" name="passenger[{{ $i }}][date_of_birth]"
                               class="form-control @error('passenger.'.$i.'.date_of_birth') is-invalid @enderror"
                               value="{{ old('passenger.'.$i.'.date_of_birth') }}"
                               max="{{ date('Y-m-d', strtotime('-1 day')) }}" required>
                        @error('passenger.'.$i.'.date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Gender</label>
                        <select name="passenger[{{ $i }}][gender]"
                                class="form-select @error('passenger.'.$i.'.gender') is-invalid @enderror" required>
                            <option value="">-- Select --</option>
                            <option value="male"   {{ old('passenger.'.$i.'.gender') === 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('passenger.'.$i.'.gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('passenger.'.$i.'.gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nationality</label>
                        <input type="text" name="passenger[{{ $i }}][nationality]"
                               class="form-control @error('passenger.'.$i.'.nationality') is-invalid @enderror"
                               value="{{ old('passenger.'.$i.'.nationality') }}"
                               placeholder="e.g. Algerian" required>
                        @error('passenger.'.$i.'.nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Passport Number</label>
                        <input type="text" name="passenger[{{ $i }}][passport_number]"
                               class="form-control @error('passenger.'.$i.'.passport_number') is-invalid @enderror"
                               value="{{ old('passenger.'.$i.'.passport_number') }}"
                               placeholder="e.g. A12345678" required>
                        @error('passenger.'.$i.'.passport_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Passport Expiry Date</label>
                        <input type="date" name="passenger[{{ $i }}][passport_expiry]"
                               class="form-control @error('passenger.'.$i.'.passport_expiry') is-invalid @enderror"
                               value="{{ old('passenger.'.$i.'.passport_expiry') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('passenger.'.$i.'.passport_expiry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        @endfor

        {{-- Summary & confirm --}}
        <div class="confirm-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="text-muted small mb-1">
                        {{ $passengerCount }} passenger(s) ·
                        {{ ['economique' => 'Economy', 'eco_premium' => 'Premium Economy', 'affaires' => 'Business', 'premiere' => 'First Class'][$request->class] ?? $request->class }} ·
                        {{ $request->type === 'aller_retour' ? 'Round Trip' : 'One Way' }}
                    </div>
                    <div class="fw-bold fs-5">
                        Total: <span class="text-primary">{{ number_format($totalPrice, 2) }} DA</span>
                    </div>
                </div>
                <div class="col-md-4 text-end mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        ✅ Confirm Booking
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@push('styles')
<style>
.booking-header {
    background: #f8faff;
    border: 1px solid #dbe8ff;
    border-radius: 14px;
    padding: 20px 24px;
}
.flight-strip {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px 18px;
}
.passenger-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.passenger-card-header {
    background: #f1f5f9;
    padding: 12px 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #e5e7eb;
}
.passenger-number {
    width: 28px;
    height: 28px;
    background: #0d6efd;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
}
.passenger-card-body {
    padding: 20px;
    background: white;
}
.confirm-box {
    background: #f0f6ff;
    border: 2px solid #c8deff;
    border-radius: 14px;
    padding: 20px 24px;
    margin-top: 24px;
}
</style>
@endpush

@endsection
