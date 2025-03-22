<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\ObatKeluar;
use Illuminate\Http\Request;

class ObatKeluarController extends Controller
{
    public function index()
    {
        $obat = Obat::with('obatKeluar')->get(); // Fetch Obat with related ObatKeluar
        return view('layouts.obat_keluar', compact('obat'));
    }

    public function create()
    {
        $obat = Obat::all(); // Ambil semua data obat untuk pilihan
        return view('layouts.create_obat_keluar', compact('obat'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah_keluar' => 'required|integer|min:1',
        ]);

        $obat = Obat::findOrFail($request->obat_id);

        // Cek apakah stok mencukupi
        if ($obat->stok < $request->jumlah_keluar) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk obat: ' . $obat->nama_obat);
        }

        // Kurangi stok obat
        $obat->update([
            'stok' => $obat->stok - $request->jumlah_keluar,
        ]);

        // Simpan data obat keluar
        ObatKeluar::create([
            'obat_id' => $request->obat_id,
            'jumlah_keluar' => $request->jumlah_keluar,
        ]);

        return redirect()->back()->with('success', 'Obat keluar berhasil ditambahkan.');
    }

}
