<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Obat;
use App\Models\ObatKeluar;
use App\Models\ObatMasuk;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obat = Obat::all(); // Ambil semua data obat
        return view('layouts.obat', compact('obat'));
    }

    public function create()
    {
        return view('layouts.create'); // Ganti dengan view form create yang sesuai
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'expired' => 'required|date',
            'stok' => 'required|integer',
        ]);

        // Simpan data obat
        Obat::create([
            'nama_obat' => $request->nama_obat,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'expired' => $request->expired,
            'stok' => $request->stok,
        ]);

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id); // Ambil data obat berdasarkan ID
        return view('layouts.edit', compact('obat')); // Ganti dengan view form edit yang sesuai
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'expired' => 'required|date',
            'stok' => 'required|integer',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'expired' => $request->expired,
            'stok' => $request->stok,
        ]);

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus.');
    }
    public function bulkDelete(Request $request)
    {
        // Validasi ID yang dipilih
        $request->validate([
            'selected_ids' => 'required|array|min:1', // Pastikan ada ID yang dipilih
            'selected_ids.*' => 'exists:obat_keluars,id', // Validasi ID yang dipilih ada di database
        ]);

        // Menghapus data yang dipilih
        ObatKeluar::whereIn('id', $request->selected_ids)->delete();

        return redirect()->route('laporan.obat-keluar')->with('success', 'Data obat keluar berhasil dihapus.');
    }


    public function createObatKeluar($obatId)
    {
        $obat = Obat::findOrFail($obatId); // Ambil data obat berdasarkan ID
        return view('layouts.obat_keluar', compact('obat'));
    }

    public function obatKeluar(Request $request, $id)
    {
        try {
            \Log::info("Proses obat keluar dimulai. ID: {$id}");

            $obat = Obat::findOrFail($id);
            \Log::info("Data obat ditemukan: ", $obat->toArray());

            $request->validate([
                'jumlah_keluar' => 'required|integer|min:1',
            ]);
            \Log::info("Validasi jumlah keluar berhasil: {$request->jumlah_keluar}");

            if ($obat->stok < $request->jumlah_keluar) {
                \Log::warning("Stok tidak mencukupi untuk obat ID: {$id}");
                return response()->json(['error' => 'Stok tidak mencukupi.'], 400);
            }

            $obat->decrement('stok', $request->jumlah_keluar);
            \Log::info("Stok berhasil dikurangi. Sisa stok: {$obat->stok}");

            DB::table('transaksi_sementara')->insert([
                'nama_obat' => $obat->nama_obat,
                'jumlah' => $request->jumlah_keluar,
                'harga' => $obat->harga_jual,
                'subtotal' => $obat->harga_jual * $request->jumlah_keluar,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \Log::info("Data berhasil disimpan ke tabel transaksi_sementara.");

            ObatKeluar::create([
                'nama_obat' => $obat->nama_obat,
                'jumlah_keluar' => $request->jumlah_keluar,
                'tanggal_keluar' => now(),
            ]);
            \Log::info("Data berhasil disimpan ke tabel obat_keluars.");

            return response()->json(['success' => 'Obat keluar berhasil diproses.'], 200);
        } catch (\Exception $e) {
            \Log::error("Error saat memproses obat keluar: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses obat keluar.'], 500);
        }
    }

    // Fungsi Laporan Obat Keluar
    public function laporanObatKeluar(Request $request)
    {
        $query = DB::table('obat_keluars');
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        $laporan = ObatKeluar::all();
        return view('laporan.obat_keluar', compact('laporan'));
    }
    // Fungsi untuk menyimpan transaksi obat keluar dan mengurangi stok obat
    public function storeObatKeluar(Request $request, $obatId)
    {
        $request->validate([
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',

        ]);

        // Ambil data obat yang akan dikeluarkan
        $obat = Obat::findOrFail($obatId);

        // Cek apakah stok obat cukup
        if ($obat->stok < $request->jumlah_keluar) {
            return redirect()->back()->with('error', 'Stok obat tidak cukup.');
        }

        // Simpan transaksi obat keluar
        ObatKeluar::create([
            'obat_id' => $obat->id,
            'jumlah_keluar' => $request->jumlah_keluar,
            'tanggal_keluar' => $request->tanggal_keluar,
        ]);

        // Kurangi stok obat
        $obat->decrement('stok', $request->jumlah_keluar);

        return redirect()->route('obat.index')->with('success', 'Obat keluar berhasil ditambahkan.');
    }

    public function indexObatMasuk()
    {
        $obat = Obat::all(); // Ambil semua data obat yang tersedia
        $obatMasuk = ObatMasuk::with('obat')->get(); // Ambil data obat masuk beserta relasi obat
        return view('obat_masuk.index', compact('obat', 'obatMasuk'));
    }

    public function storeObatMasuk(Request $request)
    {
        // Validasi data
        $request->validate([
            'obat_id' => 'required|exists:obat,id', // Pastikan obat_id ada di tabel obat
            'jumlah_masuk' => 'required|integer|min:1',
            'expired' => 'required|date|after:today',
        ]);

        // Simpan data obat masuk
        ObatMasuk::create($request->all());

        return redirect()->route('obat-masuk.index')->with('success', 'Data obat masuk berhasil ditambahkan.');
    }

    public function prosesObatMasuk()
    {
        // Ambil semua data obat masuk
        $obatMasuk = ObatMasuk::all();

        foreach ($obatMasuk as $data) {
            // Cari obat berdasarkan ID
            $obat = Obat::find($data->obat_id);

            if ($obat) {
                // Tambahkan jumlah stok obat
                $obat->update([
                    'stok' => $obat->stok + $data->jumlah_masuk,
                    'expired' => $data->expired, // Update tanggal kadaluarsa jika diperlukan
                ]);
            }
        }

        // Hapus semua data obat masuk setelah diproses
        ObatMasuk::truncate();

        return redirect()->route('obat-masuk.index')->with('success', 'Stok berhasil ditambahkan ke tabel Data Obat.');
    }
    




}