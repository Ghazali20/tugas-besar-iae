<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'date'];

    // Relasi: 1 Event bisa punya banyak tipe Tiket (VIP, Festival, dll)
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}