<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_menu',
        'harga',
        'deskripsi',
        'kategori',
        'stok',
        'is_populer',
        'image_url',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
        'is_populer' => 'boolean',
    ];

    public function pesananItems()
    {
        return $this->hasMany(PesananItem::class);
    }
}
