@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🚗 Manage Car Rentals</h2>
        <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">+ Add Car</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Car</th>
                    <th>Type</th>
                    <th>Specs</th>
                    <th>Destination</th>
                    <th>Price/day</th>
                    <th>Units</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cars as $car)
                <tr>
                    <td>{{ $car->id }}</td>
                    <td>
                        <strong>{{ $car->brand }} {{ $car->model }}</strong>
                    </td>
                    <td><span class="badge bg-secondary">{{ ucfirst($car->type) }}</span></td>
                    <td class="small text-muted">
                        🪑 {{ $car->seats }} · ⚙️ {{ ucfirst($car->transmission) }} · ⛽ {{ ucfirst($car->fuel) }}
                        @if($car->with_ac) · ❄️ AC @endif
                        @if($car->unlimited_mileage) · ∞ km @endif
                    </td>
                    <td>{{ $car->destination->name ?? '—' }}</td>
                    <td>{{ number_format($car->price_per_day, 2) }} DA</td>
                    <td>
                        <span class="badge bg-{{ $car->available_units > 0 ? 'success' : 'danger' }}">
                            {{ $car->available_units }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.cars.edit', $car) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.cars.destroy', $car) }}" method="POST"
                                  onsubmit="return confirm('Delete this car?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        No cars yet. <a href="{{ route('admin.cars.create') }}">Add the first one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $cars->links() }}
    </div>

</div>
@endsection
