<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbacController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\UserAttributeController;
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
    Route::get('/', [AbacController::class, 'index'])->name('abac.index');
    Route::get('/create', [AbacController::class, 'create'])->name('abac.create');
    Route::post('/', [AbacController::class, 'store'])->name('abac.store');
    Route::get('/{id}', [AbacController::class, 'show'])->name('abac.show');
    Route::get('/{id}/edit', [AbacController::class, 'edit'])->name('abac.edit');
    Route::put('/{id}', [AbacController::class, 'update'])->name('abac.update');
    Route::delete('/{id}', [AbacController::class, 'destroy'])->name('abac.destroy');
});

// Policies routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('abac')->name('abac.')->group(function () {
        Route::resource('policies', PolicyController::class);
        Route::resource('attributes', AttributeController::class);
        Route::resource('user-attributes', UserAttributeController::class);
        
        // Kullanıcı öznitelikleri için route'lar
        Route::get('/users/{user}/attributes', [UserAttributeController::class, 'index'])->name('users.attributes');
        Route::post('/users/{user}/attributes', [UserAttributeController::class, 'store'])->name('users.attributes.store');
        Route::put('/users/{user}/attributes/{attribute}', [UserAttributeController::class, 'update'])->name('users.attributes.update');
        Route::delete('/users/{user}/attributes/{attribute}', [UserAttributeController::class, 'destroy'])->name('users.attributes.destroy');
    });
});

require __DIR__.'/auth.php';
