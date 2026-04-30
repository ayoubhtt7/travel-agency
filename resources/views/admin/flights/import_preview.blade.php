@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🌐 Import Preview — {{ $airport->city }} ({{ $airport->code }}) {{ ucfirst($request->type) }}s</h2>
        <a href="{{ route('admin.flights.import') }}" class="btn btn-outline-secondary">← Back</a>
    </div>

    @if($error)
        <div class="alert alert-warning">{{ $error }}</div>
    @elseif(empty($flights))
        <div class="alert alert-info">No flights returned from the API.</div>
    @else
        <div class="alert alert-success">
            Found <strong>{{ count($flights) }}</strong> flights. Select the ones you want to import.
            Flights with airports not in your database will be automatically skipped.
        </div>

        <form action="{{ route('admin.flights.import.save') }}" method="POST">
            @csrf

            <div class="mb-3 d-flex gap-2">
                <button type="button" onclick="selectAll()" class="btn btn-sm btn-outline-secondary">Select All</button>
                <button type="button" onclick="selectNone()" class="btn btn-sm btn-outline-secondary">Deselect All</button>
                <button type="submit" class="btn btn-primary ms-auto">
                    Import Selected →
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="selectAllBox" onchange="toggleAll(this)"></th>
                            <th>Flight</th>
                            <th>Airline</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($flights as $f)
                        @php
                            $depTime = data_get($f, 'departure.scheduled');
                            $arrTime = data_get($f, 'arrival.scheduled');
                            $flightNum = data_get($f, 'flight.iata', '');
                            $st = data_get($f, 'flight_status', 'scheduled');
                            $badge = \App\Services\AviationStackService::statusBadge($st);
                            $encoded = base64_encode(json_encode($f));
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" class="flight-checkbox form-check-input"
                                       name="flights[]" value="{{ $encoded }}" checked>
                            </td>
                            <td><strong>{{ $flightNum }}</strong></td>
                            <td>{{ data_get($f, 'airline.name', '—') }}</td>
                            <td>
                                <strong>{{ data_get($f, 'departure.iata', '—') }}</strong>
                                <div class="text-muted small">{{ data_get($f, 'departure.airport', '') }}</div>
                            </td>
                            <td>
                                <strong>{{ data_get($f, 'arrival.iata', '—') }}</strong>
                                <div class="text-muted small">{{ data_get($f, 'arrival.airport', '') }}</div>
                            </td>
                            <td>
                                {{ $depTime ? \Carbon\Carbon::parse($depTime)->format('d M H:i') : '—' }}
                                @if(data_get($f, 'departure.delay'))
                                    <span class="badge bg-warning text-dark">+{{ data_get($f, 'departure.delay') }}m</span>
                                @endif
                            </td>
                            <td>{{ $arrTime ? \Carbon\Carbon::parse($arrTime)->format('d M H:i') : '—' }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ ucfirst($st) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-5">
                    Import Selected →
                </button>
            </div>
        </form>
    @endif

</div>

@push('scripts')
<script>
function toggleAll(cb) {
    document.querySelectorAll('.flight-checkbox').forEach(c => c.checked = cb.checked);
}
function selectAll()  { document.querySelectorAll('.flight-checkbox').forEach(c => c.checked = true);  }
function selectNone() { document.querySelectorAll('.flight-checkbox').forEach(c => c.checked = false); }
</script>
@endpush

@endsection
