<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


// 1. Identity & Session Layer
Route::middleware('auth:sanctum')->group(function () {
    // Auth-specific routes (using your existing prefix)
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Workspace Management (No 'auth' prefix here, cleaner URL: /api/workspace/switch)
    // We do NOT put 'tenant' middleware here so users can fix their company ID if it's missing.
    Route::post('/workspace/switch', [AuthController::class, 'switchWorkspace']);

    // 2. Data & Feature Layer (The Tenant-Secure Zone)
    Route::middleware(['tenant'])->group(function () {
        // New Dashboard Stats Route
        Route::get('/dashboard/stats', [DashboardController::class, 'index']);

        // Your existing project routes
        Route::apiResource('projects', ProjectController::class);
    });
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
