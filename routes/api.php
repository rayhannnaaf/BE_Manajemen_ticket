<?php

use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\KonserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
// use App\Http\Controllers\KonserController;
// use App\Http\Controllers\OrderController;
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

Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori', [KategoriController::class, 'store']);
Route::get('/kategori/{id}', [KategoriController::class, 'show']);
Route::put('/kategori/{id}', [KategoriController::class, 'update']);
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);
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

// CRUD full — hanya untuk resource "master data" admin
// Route::apiResource('kategori', KategoriController::class);
Route::apiResource('konsers', KonserController::class);
Route::apiResource('tickets', TicketController::class);

// Order: SENGAJA tidak apiResource penuh
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);      // admin
    Route::get('/orders/{id}', [OrderController::class, 'show']);  // admin
    Route::post('/orders', [OrderController::class, 'store']);     // user landing page
    Route::patch('/orders/{id}/approve', [OrderController::class, 'approve']); // admin centang
    Route::patch('/orders/{id}/reject', [OrderController::class, 'reject']);   // admin reject
});

Route::apiResource('order', OrderController::class);
Route::apiResource('ticket', TicketController::class);
// Route::apiResource('konser', KonserController::class);
});
