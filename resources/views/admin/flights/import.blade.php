@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 700px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🌐 Import Flights from AviationStack</h2>
        <a href="{{ route('admin.flights.index') }}" class="btn btn-outline-secondary">← Back</a>
    </div>

    <div class="alert alert-info">
        This will fetch real flights from the AviationStack API for a given airport
        and let you select which ones to import into your database.
        <strong>Free plan: 100 requests/month.</strong>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.flights.import.preview') }}" method="GET">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Airport</label>
                    <select name="iata_code" class="form-select" required>
                        <option value="">-- Select Airport --</option>
                        @php $currentCountry = null; @endphp
                        @foreach($airports as $airport)
                            @if($currentCountry !== $airport->country)
                                @if($currentCountry !== null)</optgroup>@endif
                                <optgroup label="{{ $airport->country }}">
                                @php $currentCountry = $airport->country; @endphp
                            @endif
                            <option value="{{ $airport->code }}">
                                {{ $airport->city }} ({{ $airport->code }}) – {{ $airport->name }}
                            </option>
                        @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Flight Direction</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="typeDep" value="departure" checked>
                            <label class="form-check-label" for="typeDep">Departures from this airport</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="typeArr" value="arrival">
                            <label class="form-check-label" for="typeArr">Arrivals to this airport</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Fetch Flights →</button>
            </form>
        </div>
    </div>

</div>
@endsection
