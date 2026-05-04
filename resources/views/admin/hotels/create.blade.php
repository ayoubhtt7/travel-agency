@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:750px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="mb-0">Add Hotel</h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Hotel details --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header fw-bold">Hotel Details</div>
            <div class="card-body p-4">

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stars <span class="text-danger">*</span></label>
                        <select name="stars" class="form-select @error('stars') is-invalid @enderror" required>
                            @foreach([5,4,3,2,1] as $s)
                                <option value="{{ $s }}" {{ old('stars') == $s ? 'selected' : '' }}>
                                    {{ $s }} {{ $s == 1 ? 'star' : 'stars' }}
                                </option>
                            @endforeach
                        </select>
                        @error('stars')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address') }}" required>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
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
                                       {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="amenity_{{ $amenity }}">
                                    {{ ucfirst(str_replace('_', ' ', $amenity)) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold">Hotel Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                           accept="image/*">
                    <div class="form-text">Max 2MB. JPEG, PNG, WebP.</div>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Rooms section --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                <span>Room Types</span>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addRoomBtn">
                    + Add Room Type
                </button>
            </div>
            <div class="card-body p-4" id="roomsContainer">
                <div class="text-muted small mb-3">
                    You can add room types now or from the hotel detail page after creating.
                </div>
                {{-- Room rows injected by JS --}}
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Create Hotel</button>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<template id="roomTemplate">
    <div class="room-row border rounded p-3 mb-3 position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-room"></button>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Type</label>
                <select name="rooms[__IDX__][type]" class="form-select form-select-sm">
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="twin">Twin</option>
                    <option value="suite">Suite</option>
                    <option value="family">Family</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Capacity</label>
                <input type="number" name="rooms[__IDX__][capacity]" class="form-control form-control-sm" min="1" value="2">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Price/night (DA)</label>
                <input type="number" name="rooms[__IDX__][price_per_night]" class="form-control form-control-sm" min="0" step="0.01" value="0">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Available</label>
                <input type="number" name="rooms[__IDX__][available_rooms]" class="form-control form-control-sm" min="0" value="1">
            </div>
            <div class="col-md-2 d-flex flex-column justify-content-end gap-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="rooms[__IDX__][with_breakfast]" value="1">
                    <label class="form-check-label small">Breakfast</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="rooms[__IDX__][refundable]" value="1" checked>
                    <label class="form-check-label small">Refundable</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
let roomIdx = 0;
document.getElementById('addRoomBtn').addEventListener('click', () => {
    const tpl  = document.getElementById('roomTemplate').innerHTML.replaceAll('__IDX__', roomIdx++);
    const div  = document.createElement('div');
    div.innerHTML = tpl;
    document.getElementById('roomsContainer').appendChild(div.firstElementChild);
});
document.getElementById('roomsContainer').addEventListener('click', e => {
    if (e.target.classList.contains('remove-room')) {
        e.target.closest('.room-row').remove();
    }
});
</script>

@endsection
