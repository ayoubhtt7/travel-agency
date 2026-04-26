@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Destinations</h2>
    <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Add Destination
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($destinations->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>No destinations yet.
        <a href="{{ route('admin.destinations.create') }}">Add the first one</a>.
    </div>
@else
    <div class="row g-4">
        @foreach($destinations as $destination)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                @if($destination->image)
                    <img src="{{ asset('storage/' . $destination->image) }}"
                         class="card-img-top" alt="{{ $destination->name }}"
                         style="height:180px;object-fit:cover;">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center text-white"
                         style="height:180px;font-size:2.5rem;">
                        <i class="bi bi-pin-map"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title mb-1">{{ $destination->name }}</h5>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-geo-alt me-1"></i>{{ $destination->country }}
                    </p>
                    @if($destination->description)
                        <p class="card-text small text-secondary" style="line-clamp:2;-webkit-line-clamp:2;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $destination->description }}
                        </p>
                    @endif
                    <span class="badge bg-info text-dark">
                        <i class="bi bi-map me-1"></i>{{ $destination->trips_count }} trip{{ $destination->trips_count !== 1 ? 's' : '' }}
                    </span>
                </div>
                <div class="card-footer bg-transparent d-flex gap-2">
                    <a href="{{ route('admin.destinations.edit', $destination) }}"
                       class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST"
                          onsubmit="return confirm('Delete this destination?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
