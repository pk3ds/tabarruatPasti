<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TabarruatController;

Route::get('/', [TabarruatController::class, 'index'])->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Tabarruat routes
Route::get('tabarruat', [TabarruatController::class, 'index'])->name('tabarruat.index');
Route::post('tabarruat', [TabarruatController::class, 'store'])->name('tabarruat.store');
Route::get('tabarruat/berjaya', [TabarruatController::class, 'success'])->name('tabarruat.success');
Route::get('api/pasti-options', [TabarruatController::class, 'getPastiOptions'])->name('api.pasti-options');

require __DIR__.'/settings.php';
