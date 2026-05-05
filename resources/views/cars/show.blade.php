@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2>{{ $car->brand }} {{ $car->model }}</h2>

    <p><strong>Type:</strong> {{ $car->type }}</p>
    <p><strong>Seats:</strong> {{ $car->seats }}</p>
    <p><strong>Price:</strong> {{ number_format($car->price_per_day, 2) }} DA/day</p>

    <p><strong>Destination:</strong> {{ $car->destination->name ?? 'N/A' }}</p>

    <a href="{{ route('cars.index') }}" class="btn btn-primary">
        Back to Cars
    </a>

</div>
@endsection