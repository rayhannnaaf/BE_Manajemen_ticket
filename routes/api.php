<?php

use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\KonserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });

//     Route::get('/users', 'UserController@index');
//     Route::get('/users/{id}', 'UserController@show');
//     Route::post('/users', 'UserController@store');
//     Route::put('/users/{id}', 'UserController@update');
//     Route::delete('/users/{id}', 'UserController@destroy');
// });

// route::get('/public', function () {
//     return response()->json(['message' => 'This is a public route']);
// });

// CRUD full — hanya untuk resource "master data" admin
Route::apiResource('kategori', KategoriController::class);
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
