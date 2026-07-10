<?php

use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\KonserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze + Sanctum, token-based)
|--------------------------------------------------------------------------
*/
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

/*
|--------------------------------------------------------------------------
| Protected Resource Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/kategori-konser', [KategoriController::class, 'index']);
    Route::post('/kategori-konser', [KategoriController::class, 'store']);
    Route::get('/kategori-konser/{id}', [KategoriController::class, 'show']);
    Route::put('/kategori-konser/{id}', [KategoriController::class, 'update']);
    Route::delete('/kategori-konser/{id}', [KategoriController::class, 'destroy']);

    Route::apiResource('order', OrderController::class);
    Route::apiResource('ticket', TicketController::class);
    // Route::apiResource('konser', KonserController::class);
});