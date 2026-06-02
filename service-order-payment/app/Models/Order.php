<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'orders';

    // Daftarkan kolom yang boleh diisi lewat Mutation GraphQL
    protected $fillable = [
        'user_id',
        'event_id',
        'quantity',
        'total_price',
        'status',
    ];
}