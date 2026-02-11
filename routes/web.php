<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\RegistrationActivityController;
use App\Http\Controllers\RegistrationController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reservation Management
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/export', [ReservationController::class, 'export'])->name('reservations.export');
    Route::post('/reservations/{reservation}/validate', [ReservationController::class, 'validateReservation'])->name('reservations.validate');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancelReservation'])->name('reservations.cancel');

    // Time Slot Management
    Route::resource('time-slots', TimeSlotController::class)->names('time_slots');

    // Room Management
    Route::resource('rooms', RoomController::class)->except(['create', 'show', 'edit']);

    // Movement Management
    Route::resource('movements', MovementController::class)->except(['create', 'show', 'edit']);

    // Activity Management
    Route::resource('activities', RegistrationActivityController::class)->except(['create', 'show', 'edit']);

    // Registration Management
    Route::get('/registrations', [RegistrationController::class, 'index'])->name('admin.registrations.index');
    Route::get('/registrations/{uuid}', [RegistrationController::class, 'show'])->name('admin.registrations.show');
    // For individual row updates if needed
    Route::post('/registrations/{uuid}/status', [RegistrationController::class, 'updateStatus'])->name('admin.registrations.update_status');

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
