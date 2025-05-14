<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiSementara extends Model
{
    protected $table = 'transaksi_sementara';
    
    protected $fillable = [
        'nama_obat',
        'jumlah',
        'harga',
        'subtotal',
        'order_id',
        'payment_method',
        'snap_token'
    ];

    // Relasi ke model Obat jika diperlukan
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'nama_obat', 'nama_obat');
    }
} 