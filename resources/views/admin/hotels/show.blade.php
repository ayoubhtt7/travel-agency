@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:860px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="mb-0">{{ $hotel->name }}</h2>
                <div class="text-warning">{{ str_repeat('★', $hotel->stars) }}{{ str_repeat('☆', 5 - $hotel->stars) }}</div>
            </div>
        </div>
        <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-warning">Edit Hotel</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Hotel summary --}}
    <div class="card border-0 bg-light mb-4">
        <div class="card-body py-2 px-3 small d-flex flex-wrap gap-3">
            <span>📍 {{ $hotel->destination->name ?? 'No destination' }}</span>
            <span>🏠 {{ $hotel->address }}</span>
            @if($hotel->amenities)
                <span>✓ {{ implode(', ', array_map(fn($a) => ucfirst(str_replace('_',' ',$a)), $hotel->amenities)) }}</span>
            @endif
        </div>
    </div>

    {{-- Room types --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Room Types ({{ $hotel->rooms->count() }})</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            + Add Room Type
        </button>
    </div>

    @if($hotel->rooms->isEmpty())
        <div class="alert alert-info">No room types yet. Add one above.</div>
    @else
    <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Type</th>
                    <th>Capacity</th>
                    <th>Price/night</th>
                    <th>Available</th>
                    <th>Options</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotel->rooms as $room)
                <tr>
                    <td><strong>{{ ucfirst($room->type) }}</strong></td>
                    <td>{{ $room->capacity }} guest{{ $room->capacity > 1 ? 's' : '' }}</td>
                    <td>{{ number_format($room->price_per_night, 2) }} DA</td>
                    <td>
                        <span class="badge bg-{{ $room->available_rooms > 0 ? 'success' : 'danger' }}">
                            {{ $room->available_rooms }}
                        </span>
                    </td>
                    <td class="small">
                        @if($room->with_breakfast)<span class="badge bg-success me-1">🍳 Breakfast</span>@endif
                        @if($room->refundable)<span class="badge bg-info text-dark">Refundable</span>
                        @else<span class="badge bg-warning text-dark">Non-refundable</span>@endif
                    </td>
                    <td>
                        <form action="{{ route('admin.hotels.rooms.destroy', $room) }}" method="POST"
                              onsubmit="return confirm('Delete this room type?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

{{-- Add Room Modal --}}
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.hotels.rooms.store', $hotel) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Room Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Room Type</label>
                            <select name="type" class="form-select" required>
                                <option value="single">Single</option>
                                <option value="double" selected>Double</option>
                                <option value="twin">Twin</option>
                                <option value="suite">Suite</option>
                                <option value="family">Family</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Max guests</label>
                            <input type="number" name="capacity" class="form-control"
                                   min="1" value="2" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Price / night (DA)</label>
                            <input type="number" name="price_per_night" class="form-control"
                                   min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Available rooms</label>
                            <input type="number" name="available_rooms" class="form-control"
                                   min="0" value="1" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Room image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12 d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="with_breakfast" value="1" id="mb">
                                <label class="form-check-label" for="mb">Breakfast included</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="refundable" value="1" id="ref" checked>
                                <label class="form-check-label" for="ref">Refundable</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
