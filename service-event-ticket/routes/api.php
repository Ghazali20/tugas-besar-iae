<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- ROUTE REST API KHUSUS BAGIAN RAFLI ---

// 1. Endpoint untuk mengambil semua daftar konser/event beserta kuota tiketnya
Route::get('/events', [EventController::class, 'index']);

// 2. Endpoint untuk membuat/menambahkan data event baru
Route::post('/events', [EventController::class, 'store']);