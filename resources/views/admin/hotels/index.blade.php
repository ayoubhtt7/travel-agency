@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🏨 Manage Hotels</h2>
        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">+ Add Hotel</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Hotel</th>
                    <th>Stars</th>
                    <th>Destination</th>
                    <th>Rooms</th>
                    <th>Amenities</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hotels as $hotel)
                <tr>
                    <td>{{ $hotel->id }}</td>
                    <td>
                        <strong>{{ $hotel->name }}</strong>
                        <div class="text-muted small">{{ $hotel->address }}</div>
                    </td>
                    <td>
                        <span class="text-warning">{{ str_repeat('★', $hotel->stars) }}</span>
                        <span class="text-muted">{{ str_repeat('☆', 5 - $hotel->stars) }}</span>
                    </td>
                    <td>{{ $hotel->destination->name ?? '—' }}</td>
                    <td>
                        <span class="badge bg-info text-dark">{{ $hotel->rooms_count }} room type{{ $hotel->rooms_count != 1 ? 's' : '' }}</span>
                    </td>
                    <td>
                        @if($hotel->amenities)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach(array_slice($hotel->amenities, 0, 3) as $a)
                                    <span class="badge bg-light text-dark border" style="font-size:0.7rem;">
                                        {{ ucfirst(str_replace('_', ' ', $a)) }}
                                    </span>
                                @endforeach
                                @if(count($hotel->amenities) > 3)
                                    <span class="badge bg-light text-muted border" style="font-size:0.7rem;">
                                        +{{ count($hotel->amenities) - 3 }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.hotels.show', $hotel) }}"
                               class="btn btn-sm btn-info text-white">Rooms</a>
                            <a href="{{ route('admin.hotels.edit', $hotel) }}"
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST"
                                  onsubmit="return confirm('Delete this hotel and all its rooms?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No hotels yet. <a href="{{ route('admin.hotels.create') }}">Add the first one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $hotels->links() }}
    </div>

</div>
@endsection
