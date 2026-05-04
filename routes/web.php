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
use App\Http\Controllers\CarRentalController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\Admin\AdminCarRentalController;
use App\Http\Controllers\Admin\AdminCarBookingController;
use App\Http\Controllers\Admin\AdminHotelController;
use App\Http\Controllers\Admin\AdminHotelBookingController;
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

    // Flight Bookings
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

// ============================================================
// ADD THESE IMPORTS at the top of web.php
// ============================================================



// ============================================================
// PUBLIC ROUTES — add alongside existing /trips and /flights
// ============================================================
Route::get('/cars', [CarRentalController::class, 'index'])->name('cars.index');
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');


// ============================================================
// AUTHENTICATED ROUTES — add inside existing auth middleware group
// ============================================================
Route::middleware('auth')->group(function () {

    // ... your existing routes ...

    // Trip booking add-ons page (shown after a trip is booked)
    Route::get('/bookings/{booking}/addons', function (\App\Models\Booking $booking) {
        abort_if($booking->user_id !== auth()->id(), 403);
        $destination = $booking->trip->destination;
        $hotels = \App\Models\Hotel::where('destination_id', $destination?->id)
            ->with('rooms')
            ->get();
        $cars = \App\Models\CarRental::where('destination_id', $destination?->id)
            ->where('available_units', '>', 0)
            ->get();
        return view('bookings.addons', compact('booking', 'hotels', 'cars'));
    })->name('booking.addons');

    // Car rentals
    Route::post('/cars/book', [CarRentalController::class, 'book'])->name('cars.book');
    Route::delete('/cars/bookings/{carBooking}', [CarRentalController::class, 'destroy'])
        ->name('cars.bookings.destroy');

    // Hotels
    Route::post('/hotels/book', [HotelController::class, 'book'])->name('hotels.book');
    Route::delete('/hotels/bookings/{hotelBooking}', [HotelController::class, 'destroy'])
        ->name('hotels.bookings.destroy');
});


// ============================================================
// ADMIN ROUTES — add inside existing admin middleware group
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // ... your existing admin routes ...

    // Car rentals CRUD
    Route::resource('cars', AdminCarRentalController::class);

    // Car bookings (read + update status + delete)
    Route::resource('car-bookings', AdminCarBookingController::class)
        ->only(['index', 'show', 'update', 'destroy']);

    // Hotels CRUD + nested room management
    Route::resource('hotels', AdminHotelController::class);
    Route::post('hotels/{hotel}/rooms', [AdminHotelController::class, 'storeRoom'])
        ->name('hotels.rooms.store');
    Route::delete('hotels/rooms/{room}', [AdminHotelController::class, 'destroyRoom'])
        ->name('hotels.rooms.destroy');

    // Hotel bookings (read + update status + delete)
    Route::resource('hotel-bookings', AdminHotelBookingController::class)
        ->only(['index', 'show', 'update', 'destroy']);
});


// ============================================================
// ALSO UPDATE BookingController::store redirect (last line):
// Replace:
//     return redirect()->route('trips.index')->with('success', 'Booking successful!');
// With:
//     return redirect()->route('booking.addons', $booking->id)
//                      ->with('success', 'Trip booked! Add a hotel or car rental below.');
// ============================================================
