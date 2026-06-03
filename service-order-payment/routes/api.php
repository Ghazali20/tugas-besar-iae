<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute bawaan Sanctum (biarkan saja jika nanti butuh auth)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//  RUTE UTAMA WEBHOOK HASURA TIM MENTALITY
Route::post('/orders', [OrderController::class, 'store']);