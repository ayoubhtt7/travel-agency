<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\CarRentalController;
use App\Http\Controllers\HotelController;
use App\Models\Booking;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('trips.index'));

Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/trips/{id}', [TripController::class, 'show'])->name('trips.show');

Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/search', [FlightController::class, 'search'])->name('flights.search');

/*
|--------------------------------------------------------------------------
| CARS + HOTELS (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/cars', [CarRentalController::class, 'index'])->name('cars.index');
Route::get('/cars/{carRental}', [CarRentalController::class, 'show'])->name('cars.show');

Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    | DASHBOARD
    */
    Route::get('/dashboard', function () {

        $user = auth()->user();

        $myBookings = $user->bookings()->with('trip')->latest()->get();

        $totalTrips = $myBookings->count();
        $totalSpent = $myBookings->sum('total_price');

        return view('dashboard', compact(
            'myBookings',
            'totalTrips',
            'totalSpent'
        ));

    })->name('dashboard');

    /*
    | BOOKINGS
    */
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/trips/{id}/book', [BookingController::class, 'create'])->name('book.create');
    Route::post('/trips/{id}/book', [BookingController::class, 'store'])->name('book.store');

    // ✅ THIS IS THE FIX (DELETE ROUTE)
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])
        ->name('bookings.destroy');

    /*
    | ADDONS PAGE
    */
    Route::get('/bookings/{booking}/addons', [BookingController::class, 'addons'])
        ->name('booking.addons');

    /*
    | FLIGHTS
    */
    Route::get('/flights/passengers', [FlightController::class, 'passengerForm'])->name('flights.passengers');
    Route::post('/flights/book', [FlightController::class, 'book'])->name('flights.book');

    /*
    | CARS BOOKING
    */
    Route::post('/cars/book', [CarRentalController::class, 'book'])->name('cars.book');

    /*
    | HOTELS BOOKING
    */
    Route::post('/hotels/book', [HotelController::class, 'book'])->name('hotels.book');

    /*
    | PROFILE
        */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', fn () => redirect()->route('admin.dashboard'));

        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'index'])
            ->name('dashboard');

        Route::resource('trips', \App\Http\Controllers\Admin\AdminTripController::class);
        Route::resource('destinations', \App\Http\Controllers\Admin\AdminDestinationController::class);
        Route::resource('flights', \App\Http\Controllers\Admin\AdminFlightController::class);

        Route::resource('bookings', \App\Http\Controllers\Admin\AdminBookingController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('flight-bookings', \App\Http\Controllers\Admin\AdminFlightBookingController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('cars', \App\Http\Controllers\Admin\AdminCarRentalController::class);
        Route::resource('car-bookings', \App\Http\Controllers\Admin\AdminCarBookingController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('hotels', \App\Http\Controllers\Admin\AdminHotelController::class);
        Route::resource('hotel-bookings', \App\Http\Controllers\Admin\AdminHotelBookingController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class)
            ->only(['index', 'update', 'destroy']);
    });

require __DIR__.'/auth.php';