@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:700px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.cars.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="mb-0">Add Car Rental</h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Brand <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                               value="{{ old('brand') }}" placeholder="e.g. Toyota" required>
                        @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Model <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                               value="{{ old('model') }}" placeholder="e.g. Corolla" required>
                        @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            @foreach(['economy','compact','suv','luxury','van','convertible'] as $t)
                                <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Transmission</label>
                        <select name="transmission" class="form-select">
                            <option value="automatic" {{ old('transmission') === 'automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="manual"    {{ old('transmission') === 'manual'    ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Fuel</label>
                        <select name="fuel" class="form-select">
                            @foreach(['petrol','diesel','electric','hybrid'] as $f)
                                <option value="{{ $f }}" {{ old('fuel') === $f ? 'selected' : '' }}>{{ ucfirst($f) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Seats</label>
                        <input type="number" name="seats" class="form-control"
                               value="{{ old('seats', 5) }}" min="1" max="20" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Price / day (DA)</label>
                        <input type="number" name="price_per_day" class="form-control"
                               value="{{ old('price_per_day') }}" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Available units</label>
                        <input type="number" name="available_units" class="form-control"
                               value="{{ old('available_units', 1) }}" min="0" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Destination</label>
                    <select name="destination_id" class="form-select">
                        <option value="">— No specific destination —</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}" {{ old('destination_id') == $dest->id ? 'selected' : '' }}>
                                {{ $dest->name }}, {{ $dest->country }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="with_ac" value="1"
                               id="with_ac" {{ old('with_ac', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="with_ac">Air conditioning</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="unlimited_mileage" value="1"
                               id="unlimited_mileage" {{ old('unlimited_mileage') ? 'checked' : '' }}>
                        <label class="form-check-label" for="unlimited_mileage">Unlimited mileage</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                           accept="image/*">
                    <div class="form-text">Max 2MB. JPEG, PNG, WebP.</div>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Car Rental</button>
                    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
