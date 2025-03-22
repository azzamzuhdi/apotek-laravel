<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatMasuk extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'obat_masuks'; // Pastikan tabel ini ada di database

    // Kolom yang dapat diisi
    protected $fillable = [
        'obat_id',
        'jumlah_masuk',
        'expired',
    ];

    // Relasi ke model Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    // Jika Anda benar-benar memiliki relasi ke LaporanStokMasuk
    public function laporanStokMasuk()
    {
        return $this->hasMany(LaporanStokMasuk::class);
    }
}
