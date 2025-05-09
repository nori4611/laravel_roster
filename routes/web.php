<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\MonitoringController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard route – disarankan masukkan dalam group juga
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [DashboardController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [DashboardController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [DashboardController::class, 'destroy'])->name('profile.destroy');

    // Roster
    Route::post('/roster/save', [RosterController::class, 'save'])->name('roster.save');
    Route::get('/roster/details/{date}', [RosterController::class, 'show'])->name('roster.details');

    // Monitoring
    Route::get('/monitorings/create', [MonitoringController::class, 'create'])->name('monitorings.create');
    Route::post('/monitorings', [MonitoringController::class, 'store'])->name('monitorings.store');
    Route::get('/monitorings/create/{date}', [MonitoringController::class, 'createWithDate'])->name('monitorings.create.withDate');

});

require __DIR__.'/auth.php';
