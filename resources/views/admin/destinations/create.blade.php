@extends('layouts.app')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.destinations.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="mb-0">Add Destination</h2>
</div>

<div class="card shadow-sm border-0" style="max-width:640px">
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="e.g. Paris" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                       value="{{ old('country') }}" placeholder="e.g. France" required>
                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="4" placeholder="Short description of this destination...">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Image</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                <div class="form-text">Max 2MB. JPEG, PNG, GIF, WebP accepted.</div>
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Create Destination
                </button>
                <a href="{{ route('admin.destinations.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
