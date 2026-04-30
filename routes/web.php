<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminTripController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFlightController;
use App\Http\Controllers\Admin\AdminFlightBookingController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('trips.index');
});

Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/trips/{id}', [TripController::class, 'show'])->name('trips.show');

// Public flight search
Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/search', [FlightController::class, 'search'])->name('flights.search');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $myBookings = auth()->user()->bookings()->with('trip')->latest()->get();
        $totalSpent = auth()->user()->bookings()->where('status', 'confirmed')->sum('total_price');
        $totalTrips = $myBookings->count();
        return view('dashboard', compact('myBookings', 'totalSpent', 'totalTrips'));
    })->name('dashboard');

    // Trip Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/trips/{id}/book', [BookingController::class, 'create'])->name('book.create');
    Route::post('/trips/{id}/book', [BookingController::class, 'store'])->name('book.store');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Flight Bookings — Step 1: passenger form, Step 2: confirm
    Route::get('/flights/passengers', [FlightController::class, 'passengerForm'])->name('flights.passengers');
    Route::post('/flights/book', [FlightController::class, 'book'])->name('flights.book');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        Route::resource('trips', AdminTripController::class);
        Route::resource('destinations', AdminDestinationController::class);
        Route::resource('flights', AdminFlightController::class);

        Route::resource('bookings', AdminBookingController::class)
            ->only(['index', 'show', 'update', 'destroy']);
        Route::resource('flight-bookings', AdminFlightBookingController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('users', AdminUserController::class)
            ->only(['index', 'update', 'destroy']);
        

    });

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
<?php

// Add this TEMPORARILY to your routes/web.php
// Visit: /debug-flights in your browser
// DELETE IT after debugging

Route::get('/debug-flights', function () {
    $dep = \App\Models\Airport::where('code', 'ALG')->first();
    $arr = \App\Models\Airport::where('code', 'CDG')->first();

    $allFlights = \App\Models\Flight::all();
    $flight24   = \App\Models\Flight::find(24);

    return response()->json([
        'dep_airport'        => $dep,
        'arr_airport'        => $arr,
        'total_flights_in_db'=> $allFlights->count(),
        'flight_24'          => $flight24,
        'flight_24_dep_id'   => $flight24?->departure_airport_id,
        'flight_24_arr_id'   => $flight24?->arrival_airport_id,
        'dep_id_matches'     => $flight24?->departure_airport_id === $dep?->id,
        'arr_id_matches'     => $flight24?->arrival_airport_id  === $arr?->id,
        'departure_at_raw'   => $flight24?->getRawOriginal('departure_at'),
        'search_date'        => '2026-06-22',
        'between_start'      => '2026-06-22 00:00:00',
        'between_end'        => '2026-06-22 23:59:59',
        'query_result'       => \App\Models\Flight::where('departure_airport_id', $dep?->id)
                                    ->where('arrival_airport_id', $arr?->id)
                                    ->where('class', 'economique')
                                    ->whereBetween('departure_at', ['2026-06-22 00:00:00', '2026-06-22 23:59:59'])
                                    ->get(),
        'without_class_filter' => \App\Models\Flight::where('departure_airport_id', $dep?->id)
                                    ->where('arrival_airport_id', $arr?->id)
                                    ->whereBetween('departure_at', ['2026-06-22 00:00:00', '2026-06-22 23:59:59'])
                                    ->get(),
        'without_date_filter'  => \App\Models\Flight::where('departure_airport_id', $dep?->id)
                                    ->where('arrival_airport_id', $arr?->id)
                                    ->get(),
    ]);
});