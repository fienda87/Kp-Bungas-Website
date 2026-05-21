<?php

use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
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

Route::get('/news', [PageController::class, 'newsIndex'])->name('news.index');
Route::get('/news/{news}', [PageController::class, 'newsShow'])->name('news.show');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('news', AdminNewsController::class);
    Route::resource('programs', AdminProgramController::class);
    
    Route::get('galleries', [AdminGalleryController::class, 'index'])->name('galleries.index');
    Route::get('galleries/{gallery}', [AdminGalleryController::class, 'show'])->name('galleries.show');
    Route::post('galleries', [AdminGalleryController::class, 'store'])->name('galleries.store');
    Route::put('galleries/{gallery}', [AdminGalleryController::class, 'update'])->name('galleries.update');
    Route::delete('galleries/{gallery}', [AdminGalleryController::class, 'destroy'])->name('galleries.destroy');
    Route::post('galleries/{gallery}/photos', [AdminGalleryController::class, 'uploadPhotos'])->name('galleries.photos.upload');
    Route::delete('galleries/photos/{photo}', [AdminGalleryController::class, 'deletePhoto'])->name('galleries.photos.destroy');
});

require __DIR__.'/auth.php';
