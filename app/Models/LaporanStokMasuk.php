<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanStokMasuk extends Model
{
    use HasFactory;

    protected $table = 'laporan_stok_masuk';

    protected $fillable = [
        'obat_id',
        'jumlah_masuk',
        'expired',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}

