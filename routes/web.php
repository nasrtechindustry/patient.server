<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentUIController;
use App\Http\Controllers\Auth\PatientLoginController;
use App\Http\Controllers\Auth\DoctorLoginController;
use App\Http\Controllers\DoctorWorkingHoursController;
use App\Http\Controllers\HomeController;

Route::get("/", function () {
    return redirect("patient/login");
});

Route::prefix('patient')->name('patient.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [PatientLoginController::class, 'showLoginForm'])->name('login');
    });
    Route::post('/login', [PatientLoginController::class, 'login']);
    Route::post('/logout', [PatientLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:patient')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.patient');
        })->name('dashboard');
    });
});


Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [DoctorLoginController::class, 'showLoginForm'])->name('login');
    });
    Route::post('/login', [DoctorLoginController::class, 'login']);
    Route::post('/logout', [DoctorLoginController::class, 'logout'])->name('logout');
    Route::put('/working-hours', [DoctorWorkingHoursController::class, 'update'])->name('working-hours.update');

    Route::middleware('auth:doctor')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.doctor');
        })->name('dashboard');
    });
});
Route::prefix('patient')->name('patient.')->middleware('auth:patient')->group(function () {
    Route::get('/appointments/create', [AppointmentUIController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentUIController::class, 'store'])->name('appointments.store');
    Route::get('/appointments', [AppointmentUIController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/{id}/cancel', [AppointmentUIController::class, 'cancel'])->name('appointments.cancel');
    Route::post('/appointments/{id}/reschedule', [AppointmentUIController::class, 'reschedule'])->name('appointments.reschedule');
});

Route::get('/appointments/available-slots', [AppointmentUIController::class, 'availableSlots'])
    ->middleware('auth:patient')
    ->name('appointments.available-slots');


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
