<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/program', [PageController::class, 'program'])->name('program');
Route::get('/perjalanan', [PageController::class, 'perjalanan'])->name('perjalanan');
Route::get('/dampak', [PageController::class, 'dampak'])->name('dampak');
Route::get('/galeri', [PageController::class, 'galeri'])->name('galeri');
Route::get('/kunjungi-kami', [PageController::class, 'kunjungiKami'])->name('kunjungi-kami');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
