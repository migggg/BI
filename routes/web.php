<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\BusinessInt\DashboardController::class, 'Dashboard'])->name('dashboard');
    Route::get('/bi/etl-dashboard', [\App\Http\Controllers\BusinessInt\DashboardController::class, 'etlDashboard'])->name('bi.etl.dashboard');
    Route::post('/bi/etl-trigger', [\App\Http\Controllers\BusinessInt\DashboardController::class, 'triggerEtl'])->name('bi.etl.trigger');
});

Route::middleware(['auth', 'role:super_admin|admin|Super Admin|Admin'])->group(function () {
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    Route::resource('offices', App\Http\Controllers\OfficeController::class);
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
