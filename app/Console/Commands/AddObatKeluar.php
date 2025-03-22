<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Obat;
use App\Models\ObatKeluar;

class AddObatKeluar extends Command
{
    protected $signature = 'obat:keluar {obatId} {jumlah_keluar} {tanggal_keluar}';
    protected $description = 'Menambahkan data obat keluar';

    public function handle()
    {
        $obatId = $this->argument('obatId');
        $jumlahKeluar = $this->argument('jumlah_keluar');
        $tanggalKeluar = $this->argument('tanggal_keluar');

        $obat = Obat::find($obatId);

        if (!$obat) {
            $this->error('Obat tidak ditemukan!');
            return;
        }

        if ($obat->stok < $jumlahKeluar) {
            $this->error('Stok obat tidak cukup.');
            return;
        }

        ObatKeluar::create([
            'obat_id' => $obatId,
            'jumlah_keluar' => $jumlahKeluar,
            'tanggal_keluar' => $tanggalKeluar,
        ]);

        $obat->decrement('stok', $jumlahKeluar);

        $this->info('Data obat keluar berhasil ditambahkan.');
    }
}