<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 

class KasirController extends Controller
{
    public function index()
    {
        $transaksi = DB::table('transaksi_sementara')->get();
        return view('layouts.kasir', compact('transaksi'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'total_belanja' => 'required|numeric|min:0',
            'uang_pembayaran' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0',
        ]);

        // Ambil data dari transaksi_sementara
        $transaksiSementara = DB::table('transaksi_sementara')->get();

        // Simpan transaksi ke tabel transaksi_final
        DB::table('transaksi_final')->insert([
            'total_belanja' => $request->total_belanja,
            'uang_pembayaran' => $request->uang_pembayaran,
            'kembalian' => $request->kembalian,
            'detail_obat' => json_encode($transaksiSementara), // Simpan detail obat dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Hapus data dari transaksi_sementara
        DB::table('transaksi_sementara')->truncate();

        return response()->json(['success' => 'Transaksi berhasil diselesaikan.']);
    }

    public function laporanPenjualan(Request $request)
    {
        $query = DB::table('transaksi_final');

        // Filter berdasarkan tanggal jika ada
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $laporan = $query->orderBy('created_at', 'desc')->get();

        // Decode JSON dari detail_obat
        foreach ($laporan as $data) {
            $data->detail_obat = $data->detail_obat ? json_decode($data->detail_obat, true) : [];
        }

        return view('laporan.penjualan', compact('laporan'));
    }

    public function eksporPDF(Request $request)
    {
        $query = DB::table('transaksi_final');

        // Filter berdasarkan tanggal jika ada
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $laporan = $query->orderBy('created_at', 'desc')->get();

        // Decode JSON dari detail_obat
        foreach ($laporan as $data) {
            $data->detail_obat = $data->detail_obat ? json_decode($data->detail_obat, true) : [];
        }

        $pdf = Pdf::loadView('laporan.pdf_penjualan', compact('laporan'));
        return $pdf->download('laporan_penjualan.pdf');
    }


}


?>