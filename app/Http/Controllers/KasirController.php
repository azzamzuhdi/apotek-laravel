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
use Carbon\Carbon;

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
        
        // Debug information
        \Log::info('Transaksi data:', ['transaksi' => $transaksi->toArray()]);
        
        return view('layouts.kasir', compact('obat', 'transaksi'));
    }

    public function tambah(Request $request)
    {
        try {
            $request->validate([
                'nama_obat' => 'required',
                'jumlah' => 'required|numeric|min:1'
            ]);

            $obat = Obat::where('nama_obat', $request->nama_obat)->first();
            
            if (!$obat) {
                return response()->json(['success' => false, 'message' => 'Obat tidak ditemukan']);
            }

            if ($obat->stok < $request->jumlah) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi']);
            }

            $subtotal = $obat->harga_jual * $request->jumlah;

            TransaksiSementara::create([
                'nama_obat' => $request->nama_obat,
                'jumlah' => $request->jumlah,
                'harga' => $obat->harga_jual,
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
            $transaksi = TransaksiSementara::find($id);
            if ($transaksi) {
                $transaksi->delete();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function total()
    {
        try {
            $total = TransaksiSementara::sum('subtotal');
            return response()->json(['total' => $total]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function proses(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'total_belanja' => 'required|numeric',
                'payment_method' => 'required|in:cash,midtrans'
            ]);

            $transaksi = TransaksiSementara::all();
            
            if ($transaksi->isEmpty()) {
                throw new \Exception('Tidak ada item dalam keranjang');
            }

            if ($request->payment_method === 'cash') {
                $request->validate([
                    'uang_pembayaran' => 'required|numeric|min:' . $request->total_belanja,
                    'kembalian' => 'required|numeric'
                ]);

                $detail_obat = $transaksi->map(function($item) {
                    return [
                        'nama_obat' => $item->nama_obat,
                        'jumlah' => $item->jumlah,
                        'harga' => $item->harga,
                        'subtotal' => $item->subtotal
                    ];
                })->toArray();

                // Simpan ke transaksi final
                TransaksiFinal::create([
                    'total_belanja' => $request->total_belanja,
                    'uang_pembayaran' => $request->uang_pembayaran,
                    'kembalian' => $request->kembalian,
                    'detail_obat' => $detail_obat,
                    'payment_method' => 'cash',
                    'transaction_status' => 'settlement'
                ]);

                // Update stok obat
                foreach ($transaksi as $item) {
                    $obat = Obat::where('nama_obat', $item->nama_obat)->first();
                    if ($obat) {
                        if ($obat->stok < $item->jumlah) {
                            throw new \Exception('Stok ' . $item->nama_obat . ' tidak mencukupi');
                        }
                        $obat->stok -= $item->jumlah;
                        $obat->save();
                    }
                }

                // Hapus transaksi sementara
                TransaksiSementara::truncate();

                DB::commit();
                return response()->json(['success' => true]);
            } else {
                // Proses pembayaran Midtrans
                $detail_obat = $transaksi->map(function($item) {
                    return [
                        'nama_obat' => $item->nama_obat,
                        'jumlah' => $item->jumlah,
                        'harga' => $item->harga,
                        'subtotal' => $item->subtotal
                    ];
                })->toArray();

                // Generate order ID
                $order_id = 'ORDER-' . time();

                // Simpan ke transaksi final
                TransaksiFinal::create([
                    'total_belanja' => $request->total_belanja,
                    'detail_obat' => $detail_obat,
                    'payment_method' => 'midtrans',
                    'order_id' => $order_id,
                    'transaction_status' => 'pending',
                    'uang_pembayaran' => $request->total_belanja,
                    'kembalian' => 0
                ]);

                // Update transaksi sementara dengan order ID
                foreach ($transaksi as $item) {
                    $item->update([
                        'order_id' => $order_id,
                        'payment_method' => 'midtrans'
                    ]);
                }

                // Parameter untuk Midtrans
                $params = [
                    'transaction_details' => [
                        'order_id' => $order_id,
                        'gross_amount' => (int) $request->total_belanja
                    ],
                    'customer_details' => [
                        'first_name' => 'Customer',
                        'email' => 'customer@example.com',
                        'phone' => '08123456789'
                    ],
                    'item_details' => array_map(function($item) {
                        return [
                            'id' => $item['nama_obat'],
                            'price' => $item['harga'],
                            'quantity' => $item['jumlah'],
                            'name' => $item['nama_obat']
                        ];
                    }, $detail_obat)
                ];

                $snapToken = Snap::getSnapToken($params);
                
                DB::commit();
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function notification(Request $request)
    {
        try {
            DB::beginTransaction();

            $payload = $request->all();
            $order_id = $payload['order_id'];
            $transaction_status = $payload['transaction_status'];
            $fraud_status = $payload['fraud_status'];

            $transaksi = TransaksiFinal::where('order_id', $order_id)->first();
            
            if ($transaksi) {
                $transaksi->update([
                    'transaction_status' => $transaction_status,
                    'transaction_id' => $payload['transaction_id'],
                    'uang_pembayaran' => $transaksi->total_belanja,
                    'kembalian' => 0
                ]);

                if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
                    // Update stok obat
                    $detail_obat = $transaksi->detail_obat;
                    foreach ($detail_obat as $item) {
                        $obat = Obat::where('nama_obat', $item['nama_obat'])->first();
                        if ($obat) {
                            if ($obat->stok < $item['jumlah']) {
                                throw new \Exception('Stok ' . $item['nama_obat'] . ' tidak mencukupi');
                            }
                            $obat->stok -= $item['jumlah'];
                            $obat->save();
                        }
                    }

                    // Hapus transaksi sementara
                    TransaksiSementara::where('order_id', $order_id)->delete();
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
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