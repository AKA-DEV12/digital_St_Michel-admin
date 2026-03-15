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
use App\Http\Controllers\PriestController;
use App\Http\Controllers\PriestAppointmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\BlogController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::middleware(['permission:access_dashboard'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/documentation', [DashboardController::class, 'documentation'])->name('admin.documentation');
    });

    // Reservation Management
    Route::middleware(['permission:access_reservations'])->group(function () {
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/export', [ReservationController::class, 'export'])->name('reservations.export');
        Route::post('/reservations/{reservation}/validate', [ReservationController::class, 'validateReservation'])->name('reservations.validate');
        Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancelReservation'])->name('reservations.cancel');
    });

    // Clergé & Rendez-vous Management
    Route::middleware(['permission:access_priests'])->group(function () {
        // Priests
        Route::patch('/priests/{priest}/toggle', [PriestController::class, 'toggleActive'])->name('admin.priests.toggle');
        Route::get('/priests/export', [PriestController::class, 'export'])->name('admin.priests.export');
        Route::resource('priests', PriestController::class)->names('admin.priests')->except(['show']);

        // Priest Appointments
        Route::get('/priest-appointments', [PriestAppointmentController::class, 'index'])->name('admin.priest_appointments.index');
        Route::get('/priest-appointments/export', [PriestAppointmentController::class, 'export'])->name('admin.priest_appointments.export');
        Route::post('/priest-appointments/{appointment}/validate', [PriestAppointmentController::class, 'validateAppointment'])->name('admin.priest_appointments.validate');
        Route::post('/priest-appointments/{appointment}/cancel', [PriestAppointmentController::class, 'cancelAppointment'])->name('admin.priest_appointments.cancel');
    });

    // Time Slot Management
    Route::middleware(['permission:access_time_slots'])->group(function () {
        Route::get('/time-slots/export', [TimeSlotController::class, 'export'])->name('time_slots.export');
        Route::resource('time-slots', TimeSlotController::class)->names('time_slots');
    });

    // Room Management
    Route::middleware(['permission:access_rooms'])->group(function () {
        Route::get('/rooms/export', [RoomController::class, 'export'])->name('rooms.export');
        Route::resource('rooms', RoomController::class)->except(['create', 'show', 'edit']);
    });

    // Movement Management
    Route::middleware(['permission:access_movements'])->group(function () {
        Route::get('/movements/export', [MovementController::class, 'export'])->name('movements.export');
        Route::resource('movements', MovementController::class)->except(['create', 'show', 'edit']);
    });

    // Activity Management
    Route::middleware(['permission:access_activities'])->group(function () {
        Route::get('/activities/export', [RegistrationActivityController::class, 'export'])->name('activities.export');
        Route::resource('activities', RegistrationActivityController::class)->except(['create', 'show', 'edit']);
    });

    // Presence / Scanned QR
    Route::middleware(['permission:access_presences'])->group(function () {
        Route::get('/registrations/scanned/export', [RegistrationController::class, 'exportScanned'])->name('admin.registrations.scanned.export');
        Route::get('/registrations/scanned', [RegistrationController::class, 'scanned'])->name('admin.registrations.scanned');
    });

    // Registration Management
    Route::middleware(['permission:access_registrations'])->group(function () {
        Route::get('/registrations/selector', [RegistrationController::class, 'selector'])->name('admin.registrations.selector');
        Route::get('/registrations/export', [RegistrationController::class, 'export'])->name('admin.registrations.export');
        Route::get('/registrations', [RegistrationController::class, 'index'])->name('admin.registrations.index');
        Route::get('/registrations/{uuid}', [RegistrationController::class, 'show'])->name('admin.registrations.show');
        Route::post('/registrations/{uuid}/status', [RegistrationController::class, 'updateStatus'])->name('admin.registrations.update_status');
        Route::delete('/registrations/{uuid}', [RegistrationController::class, 'destroy'])->name('admin.registrations.destroy');
    });

    // Participant Groups Management
    Route::middleware(['permission:access_registrations'])->group(function () {
        Route::get('/participant-groups/export', [\App\Http\Controllers\ParticipantGroupController::class, 'export'])->name('admin.participant_groups.export');
        Route::get('/participant-groups', [\App\Http\Controllers\ParticipantGroupController::class, 'index'])->name('admin.participant_groups.index');
        Route::get('/participant-groups/create', [\App\Http\Controllers\ParticipantGroupController::class, 'create'])->name('admin.participant_groups.create');
        Route::post('/participant-groups', [\App\Http\Controllers\ParticipantGroupController::class, 'store'])->name('admin.participant_groups.store');
        Route::get('/participant-groups/{id}', [\App\Http\Controllers\ParticipantGroupController::class, 'show'])->name('admin.participant_groups.show');
        Route::delete('/participant-groups/{group}', [\App\Http\Controllers\ParticipantGroupController::class, 'destroy'])->name('admin.participant_groups.destroy');
    });

    // Agent Management
    Route::middleware(['permission:access_agents'])->group(function () {
        Route::get('/agents/export', [AgentController::class, 'export'])->name('agents.export');
        Route::resource('agents', AgentController::class)->except(['create', 'show', 'edit']);
    });

    // Admin & Access Management
    Route::middleware(['permission:manage_users'])->group(function () {
        Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    });
    Route::middleware(['permission:manage_roles'])->resource('roles', RoleController::class)->except(['create', 'show', 'edit']);

    // Blog Management
    Route::middleware(['permission:access_blog'])->group(function () {
        Route::prefix('blog')->name('admin.blog.')->group(function () {
            Route::get('/categories/export', [BlogController::class, 'exportCategories'])->name('categories.export');
            Route::get('/categories', [BlogController::class, 'categories'])->name('categories');
            Route::post('/categories', [BlogController::class, 'storeCategory'])->name('categories.store');
            Route::delete('/categories/{category}', [BlogController::class, 'destroyCategory'])->name('categories.destroy');

            Route::get('/tags/export', [BlogController::class, 'exportTags'])->name('tags.export');
            Route::get('/tags', [BlogController::class, 'tags'])->name('tags');
            Route::post('/tags', [BlogController::class, 'storeTag'])->name('tags.store');
            Route::delete('/tags/{tag}', [BlogController::class, 'destroyTag'])->name('tags.destroy');
        });
        Route::get('/blog/export', [BlogController::class, 'export'])->name('admin.blog.export');
        Route::resource('blog', BlogController::class)->parameters([
            'blog' => 'post'
        ])->names([
            'index' => 'admin.blog.index',
            'create' => 'admin.blog.create',
            'store' => 'admin.blog.store',
            'edit' => 'admin.blog.edit',
            'update' => 'admin.blog.update',
            'destroy' => 'admin.blog.destroy',
        ])->except(['show']);

        Route::resource('reviews', \App\Http\Controllers\Admin\BlogReviewController::class)->names([
            'index' => 'admin.reviews.index',
            'create' => 'admin.reviews.create',
            'store' => 'admin.reviews.store',
            'edit' => 'admin.reviews.edit',
            'update' => 'admin.reviews.update',
            'destroy' => 'admin.reviews.destroy',
        ]);
    });

    // Flash Messages
    Route::middleware(['permission:access_flash_messages'])->group(function () {
        Route::get('/flash-messages/export', [\App\Http\Controllers\Admin\FlashMessageController::class, 'export'])->name('admin.flash-messages.export');
        Route::resource('flash-messages', \App\Http\Controllers\Admin\FlashMessageController::class)->names([
            'index' => 'admin.flash-messages.index',
            'create' => 'admin.flash-messages.create',
            'store' => 'admin.flash-messages.store',
            'edit' => 'admin.flash-messages.edit',
            'update' => 'admin.flash-messages.update',
            'destroy' => 'admin.flash-messages.destroy',
        ]);
    });

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Site Settings
    Route::middleware(['permission:access_settings'])->group(function () {
        Route::get('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'index'])->name('admin.settings.index');
    });
    Route::post('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('admin.settings.update');
});

require __DIR__ . '/auth.php';
