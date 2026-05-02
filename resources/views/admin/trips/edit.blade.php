@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:700px;">

    <h2 class="mb-4">Edit Trip: {{ $trip->title }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.trips.update', $trip) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $trip->title) }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description"
                      class="form-control @error('description') is-invalid @enderror"
                      rows="4" required>{{ old('description', $trip->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Price (DA)</label>
                <input type="number" name="price"
                       class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price', $trip->price) }}" min="0" step="0.01" required>
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Duration (days)</label>
                <input type="number" name="duration"
                       class="form-control @error('duration') is-invalid @enderror"
                       value="{{ old('duration', $trip->duration) }}" min="1" required>
                @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Start Date</label>
                <input type="date" name="start_date"
                       class="form-control @error('start_date') is-invalid @enderror"
                       value="{{ old('start_date', $trip->start_date?->format('Y-m-d')) }}" required>
                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">End Date</label>
                <input type="date" name="end_date"
                       class="form-control @error('end_date') is-invalid @enderror"
                       value="{{ old('end_date', $trip->end_date?->format('Y-m-d')) }}" required>
                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Available Seats</label>
                <input type="number" name="available_seats"
                       class="form-control @error('available_seats') is-invalid @enderror"
                       value="{{ old('available_seats', $trip->available_seats) }}" min="0" required>
                @error('available_seats')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Destination</label>
                <select name="destination_id"
                        class="form-select @error('destination_id') is-invalid @enderror" required>
                    <option value="">-- Select Destination --</option>
                    @foreach($destinations as $dest)
                        <option value="{{ $dest->id }}"
                            {{ old('destination_id', $trip->destination_id) == $dest->id ? 'selected' : '' }}>
                            {{ $dest->name }}, {{ $dest->country }}
                        </option>
                    @endforeach
                </select>
                @error('destination_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Trip Image</label>
            @if($trip->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $trip->image) }}"
                         alt="Current image" class="img-thumbnail"
                         style="height:120px;object-fit:cover;">
                    <small class="d-block text-muted mt-1">Upload a new image to replace.</small>
                </div>
            @endif
            <input type="file" name="image"
                   class="form-control @error('image') is-invalid @enderror"
                   accept="image/*">
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning">Update Trip</button>
            <a href="{{ route('admin.trips.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>

</div>
@endsection
