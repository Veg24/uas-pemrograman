<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meja_id',
        'tanggal',
        'jam',
        'jumlah_tamu',
        'catatan',
        'status',
        'biaya_deposit',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'biaya_deposit' => 'decimal:2',
        'jumlah_tamu' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }
}
