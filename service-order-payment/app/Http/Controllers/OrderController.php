<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Ambil bungkusan input data dari Hasura
        if ($request->has('input')) {
            $data = $request->input('input');
        } else {
            $data = $request->all();
        }

        // 2. Validasi input ticket_id dari Hasura Valdo
        $validated = validator($data, [
            'ticket_id'   => 'required|integer',
            'quantity'    => 'required|integer',
            'total_price' => 'required|numeric',
        ])->validate();

        // 3. AMANKAN USER_ID & EVENT_ID: Menyisipkan dummy user_id agar lolos validasi NOT NULL MySQL
        $orderId = DB::table('orders')->insertGetId([
            'user_id'     => 1,                       // Dummy user_id untuk bypass validasi tabel kelompokmu
            'event_id'    => $validated['ticket_id'], // Menyelaraskan nama kolom database
            'quantity'    => $validated['quantity'],
            'total_price' => $validated['total_price'],
            'status'      => 'pending',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // 4. 🎯 TRIGGER EVENT BROKER: Bungkus data transaksi untuk dilempar ke Redis Broker
        $eventData = [
            'order_id'    => $orderId,
            'ticket_id'   => (int)$validated['ticket_id'],
            'quantity'    => (int)$validated['quantity'],
            'total_price' => (float)$validated['total_price'],
            'status'      => 'pending'
        ];

        // Melempar bungkusan JSON ke channel broker bernama 'order-created'
        Redis::publish('order-created', json_encode($eventData));

        // 5. Kembalikan output sesuai struktur OrderOutput milik Hasura Valdo
        return response()->json([
            'id'          => $orderId,
            'ticket_id'   => (int)$validated['ticket_id'],
            'quantity'    => (int)$validated['quantity'],
            'total_price' => (float)$validated['total_price'],
            'status'      => 'pending',
            'message'     => 'Order Dibuat & Event Berhasil Dikirim ke Redis Broker!'
        ], 201);
    }
}