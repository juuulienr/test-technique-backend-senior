<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Api\Public\ProfileController as PublicProfileController;
use App\UI\Http\Controllers\Api\Admin\CommentController;

// Version 1 de l'API
Route::prefix('v1')->group(function () {

    // Routes d'authentification
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('v1.auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('v1.auth.login');
    });

    // Routes d'administration (protégées)
    Route::middleware(['auth:admin', 'throttle:60,1', 'admin'])->prefix('admin')->group(function () {
        // Profils
        Route::post('/profiles', [AdminProfileController::class, 'store'])->name('v1.admin.profiles.store');
        Route::put('/profiles/{profile}', [AdminProfileController::class, 'update'])
            ->middleware('owns.profile')
            ->where('profile', '[0-9]+')
            ->name('v1.admin.profiles.update');
        Route::delete('/profiles/{profile}', [AdminProfileController::class, 'destroy'])
            ->middleware('owns.profile')
            ->where('profile', '[0-9]+')
            ->name('v1.admin.profiles.destroy');

        // Commentaires
        Route::post('/profiles/{profile}/comments', [CommentController::class, 'store'])
            ->where('profile', '[0-9]+')
            ->name('v1.admin.profiles.comments.store');
    });

    // Endpoints publics
    Route::get('/profiles', [PublicProfileController::class, 'index'])->name('v1.public.profiles.index');
});
