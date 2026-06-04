<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_harga',
        'status',
        'metode_pembayaran',
        'tipe',
        'nama_penerima',
        'alamat_penerima',
        'catatan',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pesananItems()
    {
        return $this->hasMany(PesananItem::class);
    }
}
