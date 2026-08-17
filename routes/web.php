<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;

// La page "/" redirige vers le tableau de bord
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Tableau de bord : accessible seulement aux utilisateurs connectes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');
Route::get('/clients', [ClientController::class, 'index'])->name('clients');

// Routes du profil (fournies par Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('produits', ProduitController::class)->except(['show']);
});

require __DIR__.'/auth.php';