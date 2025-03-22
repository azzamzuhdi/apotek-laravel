<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StokMasuk;
use App\Models\LaporanStokMasuk;
use App\Models\ObatMasuk;
use App\Models\Obat;



class ObatMasukController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah_masuk' => 'required|integer|min:1',
            'expired' => 'required|date',
        ]);

        // Simpan data ke tabel obat_masuk
        ObatMasuk::create([
            'obat_id' => $request->obat_id,
            'jumlah_masuk' => $request->jumlah_masuk,
            'expired' => $request->expired,
        ]);

        return redirect()->back()->with('success', 'Data obat masuk berhasil ditambahkan!');
    }

    public function laporanStokMasuk()
    {
        // Ambil semua data dari tabel laporan_stok_masuk
        $laporan = LaporanStokMasuk::with('obat')->get();

        // Kirim data ke view
        return view('laporan.stok_masuk', compact('laporan'));
    }


    public function proses(Request $request)
    {
        // Ambil semua data dari tabel obat masuk
        $dataObatMasuk = ObatMasuk::all();

        // Loop untuk memindahkan data ke tabel laporan stok masuk
        foreach ($dataObatMasuk as $data) {
            LaporanStokMasuk::create([
                'obat_id' => $data->obat_id,
                'jumlah_masuk' => $data->jumlah_masuk,
                'expired' => $data->expired,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update stok di tabel obat
            $obat = Obat::find($data->obat_id);
            $obat->stok += $data->jumlah_masuk;
            $obat->save();
        }

        // Kosongkan tabel obat masuk setelah diproses
        ObatMasuk::truncate();

        return redirect()->route('obat-masuk.index')->with('success', 'Data berhasil diproses ke laporan stok masuk.');
    }



}
