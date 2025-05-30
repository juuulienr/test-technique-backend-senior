<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Api\Public\ProfileController as PublicProfileController;
use App\Http\Controllers\Api\Admin\CommentController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:admin', 'throttle:60,1', 'admin'])->prefix('admin')->group(function () {
    // Profils
    Route::post('/profiles', [AdminProfileController::class, 'store']);
    Route::put('/profiles/{profile}', [AdminProfileController::class, 'update'])->middleware('owns.profile');
    Route::delete('/profiles/{profile}', [AdminProfileController::class, 'destroy'])->middleware('owns.profile');

    // Commentaires
    Route::post('/profiles/{profile}/comments', [CommentController::class, 'store']);
});

// Endpoint public
Route::get('/profiles', [PublicProfileController::class, 'index']);
