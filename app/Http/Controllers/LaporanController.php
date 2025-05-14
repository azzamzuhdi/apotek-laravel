<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiFinal;
use PDF;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $query = TransaksiFinal::query();

        // Filter berdasarkan tanggal jika ada
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter berdasarkan metode pembayaran jika ada
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter berdasarkan status transaksi jika ada
        if ($request->transaction_status) {
            $query->where('transaction_status', $request->transaction_status);
        }

        // Hitung total penjualan dan jumlah transaksi sebelum pagination
        $totalPenjualan = (float) $query->sum('total_belanja');
        $jumlahTransaksi = (int) $query->count();

        // Urutkan berdasarkan tanggal terbaru dan paginate
        $transaksi = $query->orderBy('created_at', 'desc')
                          ->paginate(10);

        return view('laporan.penjualan', [
            'transaksi' => $transaksi,
            'totalPenjualan' => $totalPenjualan,
            'jumlahTransaksi' => $jumlahTransaksi
        ]);
    }

    public function exportPDF(Request $request)
    {
        $query = TransaksiFinal::query();

        // Filter berdasarkan tanggal jika ada
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get();
        $totalPenjualan = (float) $query->sum('total_belanja');
        $jumlahTransaksi = (int) $query->count();
        
        $pdf = PDF::loadView('laporan.penjualan-pdf', [
            'transaksi' => $transaksi,
            'totalPenjualan' => $totalPenjualan,
            'jumlahTransaksi' => $jumlahTransaksi
        ]);

        return $pdf->download('laporan-penjualan.pdf');
    }
} 