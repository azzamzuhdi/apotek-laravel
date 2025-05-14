<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSementara;
use App\Models\TransaksiFinal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PaymentService;
use Illuminate\Support\Str;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class KasirController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $obat = Obat::all();
        $transaksi = TransaksiSementara::all();
        return view('layouts.kasir', compact('obat', 'transaksi'));
    }

    public function tambah(Request $request)
    {
        try {
            $request->validate([
                'nama_obat' => 'required',
                'jumlah' => 'required|numeric|min:1',
                'harga' => 'required|numeric|min:0'
            ]);

            $subtotal = $request->jumlah * $request->harga;

            TransaksiSementara::create([
                'nama_obat' => $request->nama_obat,
                'jumlah' => $request->jumlah,
                'harga' => $request->harga,
                'subtotal' => $subtotal
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function hapus($id)
    {
        try {
            $transaksi = TransaksiSementara::findOrFail($id);
            $transaksi->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function total()
    {
        $total = TransaksiSementara::sum('subtotal');
        return response()->json(['total' => $total]);
    }

    public function proses(Request $request)
    {
        try {
            $request->validate([
                'total_belanja' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash,midtrans'
            ]);

            $transaksi = TransaksiSementara::all();
            
            if ($transaksi->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada item dalam keranjang']);
            }

            if ($request->payment_method === 'cash') {
                $request->validate([
                    'uang_pembayaran' => 'required|numeric|min:' . $request->total_belanja
                ]);

                $kembalian = $request->uang_pembayaran - $request->total_belanja;

                // Buat transaksi final
                $transaksiFinal = TransaksiFinal::create([
                    'total_belanja' => $request->total_belanja,
                    'uang_pembayaran' => $request->uang_pembayaran,
                    'kembalian' => $kembalian,
                    'detail_obat' => $transaksi->toArray(),
                    'payment_method' => 'cash',
                    'transaction_status' => 'success'
                ]);

                // Hapus transaksi sementara
                TransaksiSementara::truncate();

                return response()->json(['success' => true]);
            } else {
                // Proses pembayaran Midtrans
                $orderId = 'ORDER-' . time();
                $customerDetails = [
                    'first_name' => 'Customer',
                    'email' => 'customer@example.com',
                    'phone' => '08123456789'
                ];

                $transactionDetails = [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $request->total_belanja
                ];

                $itemDetails = $transaksi->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'price' => (int) $item->harga,
                        'quantity' => (int) $item->jumlah,
                        'name' => $item->nama_obat
                    ];
                })->toArray();

                $params = [
                    'transaction_details' => $transactionDetails,
                    'customer_details' => $customerDetails,
                    'item_details' => $itemDetails
                ];

                // Dapatkan Snap Token
                $snapToken = Snap::getSnapToken($params);

                // Update transaksi sementara dengan order_id dan snap_token
                foreach ($transaksi as $item) {
                    $item->update([
                        'order_id' => $orderId,
                        'payment_method' => 'midtrans',
                        'snap_token' => $snapToken
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function notification(Request $request)
    {
        $notif = new \Midtrans\Notification();
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if($fraud == 'challenge') {
                    $status = 'challenge';
                } else {
                    $status = 'success';
                }
            }
        } else if ($transaction == 'settlement') {
            $status = 'success';
        } else if ($transaction == 'pending') {
            $status = 'pending';
        } else if ($transaction == 'deny') {
            $status = 'denied';
        } else if ($transaction == 'expire') {
            $status = 'expired';
        } else if ($transaction == 'cancel') {
            $status = 'canceled';
        }

        // Update transaksi final
        $transaksiSementara = TransaksiSementara::where('order_id', $orderId)->get();
        
        if ($transaksiSementara->isNotEmpty()) {
            TransaksiFinal::create([
                'total_belanja' => $transaksiSementara->sum('subtotal'),
                'detail_obat' => $transaksiSementara->toArray(),
                'payment_method' => 'midtrans',
                'order_id' => $orderId,
                'transaction_status' => $status,
                'transaction_id' => $notif->transaction_id
            ]);

            // Hapus transaksi sementara
            TransaksiSementara::where('order_id', $orderId)->delete();
        }

        return response()->json(['success' => true]);
    }

    public function laporanPenjualan(Request $request)
    {
        $query = TransaksiFinal::query();

        // Filter berdasarkan tanggal jika ada
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $laporan = $query->orderBy('created_at', 'desc')->get();

        return view('laporan.penjualan', compact('laporan'));
    }

    public function eksporPDF(Request $request)
    {
        $query = TransaksiFinal::query();

        // Filter berdasarkan tanggal jika ada
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $laporan = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('laporan.pdf_penjualan', compact('laporan'));
        return $pdf->download('laporan_penjualan.pdf');
    }
}


?>