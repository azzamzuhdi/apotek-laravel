<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ObatKeluar;
use App\Models\Obat;

class ObatKeluarSeeder extends Seeder
{
    public function run()
    {
        $obat = Obat::first(); // Ambil obat pertama sebagai contoh

        ObatKeluar::create([
            'obat_id' => $obat->id,
            'jumlah_keluar' => 10,
            'tanggal_keluar' => now(),
        ]);

        // Kurangi stok obat
        $obat->decrement('stok', 10);
    }
}