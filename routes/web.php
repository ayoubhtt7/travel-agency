<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\CarRentalController;
use App\Http\Controllers\HotelController;

// ADMIN
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminTripController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFlightController;
use App\Http\Controllers\Admin\AdminFlightBookingController;
use App\Http\Controllers\Admin\AdminCarRentalController;
use App\Http\Controllers\Admin\AdminCarBookingController;
use App\Http\Controllers\Admin\AdminHotelController;
use App\Http\Controllers\Admin\AdminHotelBookingController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('trips.index'));

Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/trips/{id}', [TripController::class, 'show'])->name('trips.show');

Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/search', [FlightController::class, 'search'])->name('flights.search');

/* ✅ ADD THESE */
Route::get('/cars', [CarRentalController::class, 'index'])->name('cars.index');
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $myBookings = auth()->user()->bookings()->latest()->get();
        return view('dashboard', compact('myBookings'));
    })->name('dashboard');

    // Trips
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/trips/{id}/book', [BookingController::class, 'create'])->name('book.create');
    Route::post('/trips/{id}/book', [BookingController::class, 'store'])->name('book.store');

    // Flights
    Route::get('/flights/passengers', [FlightController::class, 'passengerForm'])->name('flights.passengers');
    Route::post('/flights/book', [FlightController::class, 'book'])->name('flights.book');

    // Cars
    Route::post('/cars/book', [CarRentalController::class, 'book'])->name('cars.book');

    // Hotels
    Route::post('/hotels/book', [HotelController::class, 'book'])->name('hotels.book');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});


/*
|--------------------------------------------------------------------------
| ADMIN
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

        // ✅ ADD THESE
        Route::resource('cars', AdminCarRentalController::class);
        Route::resource('car-bookings', AdminCarBookingController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('hotels', AdminHotelController::class);
        Route::resource('hotel-bookings', AdminHotelBookingController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('users', AdminUserController::class)
            ->only(['index', 'update', 'destroy']);
    });

require __DIR__.'/auth.php';