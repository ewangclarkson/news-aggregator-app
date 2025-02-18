<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\ArticleController;

/**
 * API Routes for user authentication and preferences management.
 *
 * This set of routes handles user registration, login, logout,
 * user preferences retrieval and storage, and article searching.
 */


// User Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);

// Auth APIs
Route::middleware('auth:api')->group(function () {
    Route::get('/user/preferences', [UserPreferenceController::class, 'index']);
    Route::post('/user/preferences', [UserPreferenceController::class, 'store']);
    Route::get('/news/sources', [UserPreferenceController::class, 'availableNewsSources']);
    Route::get('/categories_authors',[ArticleController::class, 'getUniqueCategoriesAndAuthors']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::post('/articles', [ArticleController::class, 'search']);
});
