<?php

use App\Http\Controllers\Api\KategoriController;
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

Route::get('/kategori-konser', [KategoriController::class, 'index']);
Route::post('/kategori-konser', [KategoriController::class, 'store']);
Route::get('/kategori-konser/{id}', [KategoriController::class, 'show']);
Route::put('/kategori-konser/{id}', [KategoriController::class, 'update']);
Route::delete('/kategori-konser/{id}', [KategoriController::class, 'destroy']);

Route::apiResource('order', \App\Http\Controllers\OrderController::class);
Route::apiResource('ticket', \App\Http\Controllers\TicketController::class);
Route::apiResource('konser', \App\Http\Controllers\KonserController::class);
