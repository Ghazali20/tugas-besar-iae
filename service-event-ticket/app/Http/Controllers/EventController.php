<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // API Mendapatkan semua daftar Event + Tiketnya
    public function index()
    {
        $events = Event::with('tickets')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Event Berhasil Diambil',
            'data'    => $events
        ], 200);
    }

    // API Membuat Event Baru (Sudah Support Bungkus Input Hasura)
    public function store(Request $request)
    {
        // 1. Cek apakah request datang dari Hasura (datanya terbungkus di dalam 'input')
        if ($request->has('input')) {
            $data = $request->input('input');
        } else {
            // Jika ditembak langsung via Postman biasa tanpa Hasura
            $data = $request->all();
        }

        // 2. Lakukan validasi manual terhadap data yang sudah dikeluarkan dari bungkusan
        $validated = validator($data, [
            'name'     => 'required|string',
            'location' => 'required|string',
            'date'     => 'required|date',
        ])->validate();

        // 3. Simpan data ke database MySQL
        $event = Event::create($validated);

        // 4. Return respon sesuai dengan format output GraphQL Action Hasura
        return response()->json([
            'id'      => $event->id,
            'name'    => $event->name,
            'message' => 'Event Berhasil Dibuat',
        ], 201);
    }
}