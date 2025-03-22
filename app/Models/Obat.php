<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ObatKeluar;
use Illuminate\Http\Request;


class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'harga_beli',
        'harga_jual',
        'expired',
        'stok',
    ];

    // Relasi One-to-Many dengan ObatKeluar
    public function obatKeluar(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);

        // Kurangi stok obat
        if ($obat->stok > 0) {
            $obat->stok -= 1;
            $obat->save();

            // Simpan data ke tabel obat keluar
            ObatKeluar::create([
                'nama_obat' => $obat->nama_obat,
                'jumlah_keluar' => 1,
                'tanggal_keluar' => now(),
            ]);

            return redirect()->back()->with('success', 'Obat berhasil dikurangi stoknya!');
        }

        return redirect()->back()->with('error', 'Stok obat habis!');
    }

}
