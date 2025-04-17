<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ABACController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ABAC Routes
Route::prefix('abac')->middleware(['auth'])->group(function () {
    Route::get('/', [ABACController::class, 'index'])->name('abac.index');
    Route::get('/create', [ABACController::class, 'create'])->name('abac.create');
    Route::post('/', [ABACController::class, 'store'])->name('abac.store');
    Route::get('/{id}', [ABACController::class, 'show'])->name('abac.show');
    Route::get('/{id}/edit', [ABACController::class, 'edit'])->name('abac.edit');
    Route::put('/{id}', [ABACController::class, 'update'])->name('abac.update');
    Route::delete('/{id}', [ABACController::class, 'destroy'])->name('abac.destroy');
});

require __DIR__.'/auth.php';
