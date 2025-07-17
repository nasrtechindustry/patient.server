<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentUIController;
use App\Http\Controllers\Auth\PatientLoginController;
use App\Http\Controllers\Auth\DoctorLoginController;
use App\Http\Controllers\DoctorWorkingHoursController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\DoctorExceptionController;
use App\Http\Controllers\DoctorExportController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\DoctorAppointmentController;
use App\Http\Controllers\Auth\PatientRegisterController;

Route::get("/", function () {
    return view("landing");
})->name("home");

// Routes for admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:admin'])->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', fn() => view('dashboard.admin'))->name('dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});


// Routes for doctor 
Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::middleware('guest:doctor')->group(function () {
        Route::get('/login', [DoctorLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [DoctorLoginController::class, 'login']);
    });

    Route::middleware('auth:doctor')->group(function () {
        Route::post('logout', [DoctorLoginController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::post('exceptions', [DoctorExceptionController::class, 'store'])->name('exceptions.add');
        Route::get('working-hours/edit', [DoctorWorkingHoursController::class, 'edit'])->name('working-hours.edit');
        Route::put('working-hours', [DoctorWorkingHoursController::class, 'update'])->name('working-hours.update');
        Route::post('exceptions', [DoctorExceptionController::class, 'store'])->name('exceptions.add');
        Route::delete('exceptions/{id}', [DoctorExceptionController::class, 'destroy'])->name('exceptions.remove');
        Route::get('export/schedule', [DoctorExportController::class, 'export'])->name('export.schedule');
        Route::get('profile', [DoctorDashboardController::class, 'profile'])->name('dashboard.profile');
        Route::get('working-hours', [DoctorDashboardController::class, 'workingHours'])->name('dashboard.working-hours');
        Route::get('exceptions', [DoctorDashboardController::class, 'exceptions'])->name('dashboard.exceptions');
        Route::get('appointments', [DoctorDashboardController::class, 'appointments'])->name('dashboard.appointments');
        Route::get('export', [DoctorDashboardController::class, 'export'])->name('dashboard.export');
        Route::post('/appointments/{appointment}/cancel', [DoctorAppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::get('/appointments/{appointment}/reschedule', [DoctorAppointmentController::class, 'showRescheduleForm'])->name('appointments.reschedule.form');
        Route::post('/appointments/{appointment}/reschedule', [DoctorAppointmentController::class, 'reschedule'])->name('appointments.reschedule');
    });
});

// Routes for patients
Route::prefix('patient')->name('patient.')->group(function () {
    Route::middleware(['guest:patient'])->group(function () {
        Route::get('/login', [PatientLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [PatientLoginController::class, 'login']);
        Route::post('/logout', [PatientLoginController::class, 'logout'])->name('logout');
    });

    Route::middleware('auth:patient')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.patient');
        })->name('dashboard');
        Route::get('/appointments/create', [AppointmentUIController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentUIController::class, 'store'])->name('appointments.store');
        Route::get('/appointments', [AppointmentUIController::class, 'index'])->name('appointments.index');
        Route::post('/appointments/{id}/cancel', [AppointmentUIController::class, 'cancel'])->name('appointments.cancel');
        Route::post('/appointments/{id}/reschedule', [AppointmentUIController::class, 'reschedule'])->name('appointments.reschedule');
    });
});




Route::get('/appointments/available-slots', [AppointmentUIController::class, 'availableSlots'])
    ->middleware('auth:patient')
    ->name('appointments.available-slots');

// Public Patient Registration
Route::get('/register/patient', [PatientRegisterController::class, 'showForm'])->name('patient.register.form');
Route::post('/register/patient', [PatientRegisterController::class, 'register'])->name('patient.register');
