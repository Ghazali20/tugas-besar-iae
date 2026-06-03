<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class RedisPaymentWorker extends Command
{
    // Nama perintah yang akan kita panggil di terminal nanti
    protected $signature = 'worker:redis-payment';

    // Deskripsi singkat worker
    protected $description = 'Worker otomatis untuk memproses payment berdasarkan event dari Redis Broker';

    public function handle()
    {
        $this->info('Sistem Worker Payment Mentality Berhasil Menyala... Menunggu Event Masuk... 📡');

        // Menguping channel Redis (ingat, Laravel otomatis memberi prefix laravel-database-)
        Redis::psubscribe(['*order-created'], function ($message, $channel) {
            $this->line("");
            $this->info("=== [EVENT DITERIMA] Ada Pesan Masuk dari Channel: {$channel} ===");

            // 1. Decode data JSON order yang dikirim dari Redis
            $orderData = json_decode($message, true);

            if (!$orderData) {
                $this->error('Gagal membaca data JSON event!');
                return;
            }

            $orderId    = $orderData['order_id'];
            $totalPrice = $orderData['total_price'];

            $this->warn("Sedang memproses pembayaran untuk Order ID: {$orderId} sebesar Rp " . number_format($totalPrice, 0, ',', '.'));

            // 2. Simulasi Logika Pembayaran (Payment Gateway)
            $this->comment("Mengirimkan status ke database: Mengubah status order menjadi 'paid'...");

            // 3. 🎯 UPDATE DISINI: Menggunakan 'paid' (Huruf Kecil)
            // 💡 Note: Jika nanti masih truncated, langsung ganti 'paid' di bawah menjadi angka 1 (tanpa petir)
            DB::table('orders')
                ->where('id', $orderId)
                ->update([
                    'status'     => 'paid',
                    'updated_at' => now(),
                ]);

            $this->info("🎯 [SUKSES TOTAL] Pembayaran Order ID: {$orderId} Berhasil Dilunasi & Status Database Diperbarui!");
            $this->line("========================================================================");
        });
    }
}