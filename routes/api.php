<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/news', [NewsController::class, 'index']);
Route::get('/categories', [NewsController::class, 'categories']);
Route::get('/news/{news}', [NewsController::class, 'show']);

Route::post('/subscribe', [NewsletterController::class, 'subscribe']);
