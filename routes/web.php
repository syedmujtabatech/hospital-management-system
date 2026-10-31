<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\IpdController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DepartmentController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);

    // Doctors
    Route::resource('doctors', DoctorController::class);

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::post('/appointments/{id}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');

    // OPD & Prescriptions
    Route::get('/opd', [OpdController::class, 'index'])->name('opd.index');
    Route::get('/opd/create', [OpdController::class, 'create'])->name('opd.create');
    Route::post('/opd', [OpdController::class, 'store'])->name('opd.store');
    Route::get('/opd/{id}', [OpdController::class, 'show'])->name('opd.show');
    Route::get('/prescriptions/{id}/print', [OpdController::class, 'printPrescription'])->name('prescriptions.print');

    // IPD & Wards
    Route::get('/ipd', [IpdController::class, 'index'])->name('ipd.index');
    Route::get('/ipd/rooms', [IpdController::class, 'rooms'])->name('ipd.rooms');
    Route::get('/ipd/create', [IpdController::class, 'create'])->name('ipd.create');
    Route::post('/ipd', [IpdController::class, 'store'])->name('ipd.store');
    Route::post('/ipd/{id}/discharge', [IpdController::class, 'discharge'])->name('ipd.discharge');
    Route::post('/rooms/{id}/status', [IpdController::class, 'updateRoomStatus'])->name('rooms.status');

    // Pharmacy
    Route::get('/pharmacy', [PharmacyController::class, 'index'])->name('pharmacy.index');
    Route::get('/pharmacy/create', [PharmacyController::class, 'create'])->name('pharmacy.create');
    Route::post('/pharmacy', [PharmacyController::class, 'store'])->name('pharmacy.store');
    Route::get('/pharmacy/{id}/edit', [PharmacyController::class, 'edit'])->name('pharmacy.edit');
    Route::put('/pharmacy/{id}', [PharmacyController::class, 'update'])->name('pharmacy.update');
    Route::post('/pharmacy/{id}/stock', [PharmacyController::class, 'addStock'])->name('pharmacy.stock');
    Route::delete('/pharmacy/{id}', [PharmacyController::class, 'destroy'])->name('pharmacy.destroy');

    // Laboratory
    Route::get('/laboratory', [LaboratoryController::class, 'index'])->name('laboratory.index');
    Route::get('/laboratory/tests', [LaboratoryController::class, 'tests'])->name('laboratory.tests');
    Route::get('/laboratory/order', [LaboratoryController::class, 'createOrder'])->name('laboratory.order');
    Route::post('/laboratory/order', [LaboratoryController::class, 'storeOrder'])->name('laboratory.storeOrder');
    Route::get('/laboratory/{id}/result', [LaboratoryController::class, 'editResult'])->name('laboratory.result');
    Route::post('/laboratory/{id}/result', [LaboratoryController::class, 'updateResult'])->name('laboratory.updateResult');
    Route::get('/laboratory/{id}/report', [LaboratoryController::class, 'printReport'])->name('laboratory.report');

    // Billing
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/create', [BillingController::class, 'create'])->name('billing.create');
    Route::post('/billing', [BillingController::class, 'store'])->name('billing.store');
    Route::get('/billing/{id}', [BillingController::class, 'show'])->name('billing.show');
    Route::get('/billing/{id}/print', [BillingController::class, 'printInvoice'])->name('billing.print');
    Route::post('/billing/{id}/payment', [BillingController::class, 'addPayment'])->name('billing.payment');

    // Departments
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
});
