<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokMasuk;
use App\Models\LaporanStokMasuk;

class LaporanStokMasukController extends Controller
{
    public function index(Request $request)
    {
        // Filter berdasarkan tanggal
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $stokMasuk = StokMasuk::when($tanggalAwal && $tanggalAkhir, function ($query) use ($tanggalAwal, $tanggalAkhir) {
            return $query->whereBetween('tanggal_masuk', [$tanggalAwal, $tanggalAkhir]);
        })->get();

        return view('laporan.stok_masuk', compact('stokMasuk', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function laporanStokMasuk()
    {
        // Ambil semua data laporan stok masuk
        $laporan = LaporanStokMasuk::with('obat')->get();
        dd($laporan);

        // Kirim data ke view
        return view('laporan.stok_masuk', compact('laporan'));
    }

}

