<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/program', function () {
    return Inertia::render('Program');
});

Route::get('/perjalanan', function () {
    return Inertia::render('Perjalanan');
});

Route::get('/dampak', function () {
    return Inertia::render('Dampak');
});

Route::get('/galeri', function () {
    return Inertia::render('Galeri');
});

Route::get('/kunjungi-kami', function () {
    return Inertia::render('KunjungiKami');
});
