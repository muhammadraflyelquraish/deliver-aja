<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    UserController,
    AddressController,
    ServiceController,
    DeliverController,
    DeliverTrackingController,
    DashboardController
};

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/users', UserController::class);
    Route::get('/users/{user}/address', [UserController::class, 'address'])->name('address.index');
    Route::get('/users/{user}/address/data', [UserController::class, 'address_data'])->name('address.data');

    Route::post('/users/{user}/address', [UserController::class, 'address_store'])->name('address.store');
    Route::patch('/users/{user}/address/{address}', [UserController::class, 'address_update'])->name('address.update');
    Route::delete('/users/{user}/address/{address}', [UserController::class, 'address_destroy'])->name('address.destroy');

    Route::resource('/service', ServiceController::class);

    Route::get('/deliver/data', [DeliverController::class, 'data'])->name('deliver.data');
    Route::get('/deliver/customer', [DeliverController::class, 'search_customer'])->name('deliver.customer');
    Route::get('/deliver/address/{user}', [DeliverController::class, 'search_address'])->name('deliver.address');

    Route::resource('/deliver', DeliverController::class);
    Route::get('/deliver/{deliver}/tracking/data', [DeliverController::class, 'tracking_data'])->name('tracking.data');
    Route::post('/deliver/{deliver}/tracking', [DeliverController::class, 'tracking_store'])->name('tracking.store');
    Route::patch('/deliver/{deliver}/tracking/{tracking}', [DeliverController::class, 'tracking_update'])->name('tracking.update');
    Route::delete('/deliver/{deliver}/tracking/{tracking}', [DeliverController::class, 'tracking_destroy'])->name('tracking.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
