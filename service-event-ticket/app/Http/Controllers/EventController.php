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

    // API Membuat Event Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'date' => 'required|date',
        ]);

        $event = Event::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Event Berhasil Dibuat',
            'data'    => $event
        ], 201);
    }
}