<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = 'stok_masuk';

    protected $fillable = [
        'nama_obat',
        'jumlah',
        'harga_beli',
        'tanggal_masuk',
        'total_harga',
    ];
}

