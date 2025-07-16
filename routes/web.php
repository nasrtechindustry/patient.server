<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentUIController;
use App\Http\Controllers\Auth\PatientLoginController;
use App\Http\Controllers\Auth\DoctorLoginController;
use App\Http\Controllers\DoctorWorkingHoursController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\DoctorExceptionController;
use App\Http\Controllers\DoctorExportController;
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
