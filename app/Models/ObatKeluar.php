<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatKeluar extends Model
{
    use HasFactory;

    protected $table = 'obat_keluars';
    protected $fillable = [
        'obat_id', 
        'nama_obat',      
        'jumlah_keluar',  // Jumlah obat yang keluar
        'tanggal_keluar', // Tanggal obat keluar
    ];  

    // Relasi ke model Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
