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
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::middleware(['permission:access_dashboard'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reservation Management
    Route::middleware(['permission:access_reservations'])->group(function () {
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/export', [ReservationController::class, 'export'])->name('reservations.export');
        Route::post('/reservations/{reservation}/validate', [ReservationController::class, 'validateReservation'])->name('reservations.validate');
        Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancelReservation'])->name('reservations.cancel');
    });

    // Time Slot Management
    Route::middleware(['permission:access_time_slots'])->resource('time-slots', TimeSlotController::class)->names('time_slots');

    // Room Management
    Route::middleware(['permission:access_rooms'])->resource('rooms', RoomController::class)->except(['create', 'show', 'edit']);

    // Movement Management
    Route::middleware(['permission:access_movements'])->resource('movements', MovementController::class)->except(['create', 'show', 'edit']);

    // Activity Management
    Route::middleware(['permission:access_activities'])->resource('activities', RegistrationActivityController::class)->except(['create', 'show', 'edit']);

    // Registration Management
    Route::middleware(['permission:access_registrations'])->group(function () {
        Route::get('/registrations', [RegistrationController::class, 'index'])->name('admin.registrations.index');
        Route::get('/registrations/{uuid}', [RegistrationController::class, 'show'])->name('admin.registrations.show');
        Route::post('/registrations/{uuid}/status', [RegistrationController::class, 'updateStatus'])->name('admin.registrations.update_status');
    });

    // Presence / Scanned QR
    Route::middleware(['permission:access_presences'])->get('/registrations/scanned', [RegistrationController::class, 'scanned'])->name('admin.registrations.scanned');

    // Agent Management
    Route::middleware(['permission:access_agents'])->resource('agents', AgentController::class)->except(['create', 'show', 'edit']);

    // Admin & Access Management
    Route::middleware(['permission:manage_users'])->resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::middleware(['permission:manage_roles'])->resource('roles', RoleController::class)->except(['create', 'show', 'edit']);

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
