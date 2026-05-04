@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:750px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.hotels.show', $hotel) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="mb-0">Edit — {{ $hotel->name }}</h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $hotel->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stars</label>
                        <select name="stars" class="form-select" required>
                            @foreach([5,4,3,2,1] as $s)
                                <option value="{{ $s }}" {{ old('stars', $hotel->stars) == $s ? 'selected' : '' }}>
                                    {{ $s }} {{ $s == 1 ? 'star' : 'stars' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address', $hotel->address) }}" required>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Destination</label>
                    <select name="destination_id" class="form-select">
                        <option value="">— No specific destination —</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}"
                                    {{ old('destination_id', $hotel->destination_id) == $dest->id ? 'selected' : '' }}>
                                {{ $dest->name }}, {{ $dest->country }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $hotel->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Amenities</label>
                    <div class="row g-2">
                        @foreach(['wifi','pool','gym','breakfast','parking','spa','restaurant','airport_shuttle'] as $amenity)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="amenities[]" value="{{ $amenity }}"
                                       id="amenity_{{ $amenity }}"
                                       {{ in_array($amenity, old('amenities', $hotel->amenities ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="amenity_{{ $amenity }}">
                                    {{ ucfirst(str_replace('_', ' ', $amenity)) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Hotel Image</label>
                    @if($hotel->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $hotel->image) }}"
                                 class="rounded" style="height:100px;object-fit:cover;" alt="Current">
                            <div class="form-text">Upload a new image to replace.</div>
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                           accept="image/*">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">Save Changes</button>
                    <a href="{{ route('admin.hotels.show', $hotel) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
