<?php

namespace App\Http\Controllers;
use App\Models\StokMasuk;
use App\Models\Obat;


use Illuminate\Http\Request;

class StokController extends Controller
{
    public function tambahStokMasuk(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah_masuk' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
        ]);

        // Simpan data ke tabel stok_masuk
        StokMasuk::create([
            'obat_id' => $request->obat_id,
            'jumlah_masuk' => $request->jumlah_masuk,
            'tanggal_masuk' => $request->tanggal_masuk,
        ]);

        // Update stok di tabel obat
        $obat = Obat::find($request->obat_id);
        $obat->stok += $request->jumlah_masuk;
        $obat->save();

        return redirect()->route('laporan.stokMasuk')->with('success', 'Stok obat berhasil ditambahkan.');
    }

    public function laporanStokMasuk()
    {
        $stokMasuk = StokMasuk::with('obat')->orderBy('tanggal_masuk', 'desc')->get();

        return view('laporan_stok_masuk', compact('stokMasuk'));
    }



}
