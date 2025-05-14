<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiFinal extends Model
{
    protected $table = 'transaksi_final';
    
    protected $fillable = [
        'total_belanja',
        'uang_pembayaran',
        'kembalian',
        'detail_obat',
        'payment_method',
        'order_id',
        'transaction_status',
        'transaction_id'
    ];

    protected $casts = [
        'detail_obat' => 'array',
        'total_belanja' => 'decimal:2',
        'uang_pembayaran' => 'decimal:2',
        'kembalian' => 'decimal:2'
    ];

    // Relasi ke user jika diperlukan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method untuk mendapatkan detail obat
    public function getDetailObatAttribute($value)
    {
        return json_decode($value, true);
    }

    // Helper method untuk menyimpan detail obat
    public function setDetailObatAttribute($value)
    {
        $this->attributes['detail_obat'] = json_encode($value);
    }
} 