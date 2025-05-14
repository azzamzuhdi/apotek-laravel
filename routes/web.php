<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ObatMasukController;
use App\Http\Controllers\LaporanStokMasukController;
use App\Http\Controllers\ObatKeluarController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StokController;

// Route untuk halaman utama


// Route untuk autentikasi
Auth::routes();

// Route untuk halaman home
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route resource untuk ObatController

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

Route::middleware(['auth', 'ceklevel:admin,user'])->group(function () {
    Route::get('/', [WebController::class, 'index']);
    Route::resource('obat', ObatController::class);
    Route::get('/obat/create', [ObatController::class, 'create'])->name('obat.create');
    Route::post('/obat', [ObatController::class, 'store'])->name('obat.store');
    Route::post('/obat/keluar/{id}', [ObatController::class, 'obatKeluar'])->name('obat.keluar');
    Route::get('/laporan/obat-keluar', [ObatController::class, 'laporanObatKeluar'])->name('laporan.obat-keluar');
    Route::get('/obat/{id}/edit', [ObatController::class, 'edit'])->name('obat.edit');
    // Route::get('/obat/edit', [ObatController::class, 'edit'])->name('obat.edit');
    Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name(name: 'obat.destroy');
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    // Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/proses', [KasirController::class, 'proses'])->name('kasir.proses');
    Route::get('/laporan-penjualan', [KasirController::class, 'laporanPenjualan'])->name('laporan.penjualan');
    Route::get('/laporan-penjualan-pdf', [KasirController::class, 'eksporPDF'])->name('laporan.penjualan.pdf');

    Route::get('/laporan/stok-masuk', [StokController::class, 'laporanStokMasuk'])->name('laporan.stokMasuk');
    Route::post('/tambah-stok-masuk', [StokController::class, 'tambahStokMasuk'])->name('stok.tambahMasuk');

    Route::get('/obat-masuk', [ObatController::class, 'indexObatMasuk'])->name('obat-masuk.index');
    Route::post('/obat-masuk/store', [ObatController::class, 'storeObatMasuk'])->name('obat-masuk.store');
    Route::post('/obat-masuk/proses', [ObatController::class, 'prosesObatMasuk'])->name('obat-masuk.proses');

    Route::post('/obat-masuk/proses', [ObatMasukController::class, 'proses'])->name('obat-masuk.proses');

    Route::get('/laporan/stok-masuk', [LaporanStokMasukController::class, 'index'])->name('laporan.stok_masuk');
    Route::get('/laporan-stok-masuk', [ObatMasukController::class, 'laporanStokMasuk'])->name('laporan-stok-masuk.index');



});

Route::middleware(['auth', 'ceklevel:admin'])->group(function () {
    // Route::get('/kasir', [KasirController::class, 'kasir']);
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::get('/laporanStok', [WebController::class, 'laporanStok']);
    // Route::get('/obatMasuk', [WebController::class, 'obatMasuk']);
    Route::get('/laporanKasir', [WebController::class, 'laporanKasir']);
    Route::get('/kasir/create', [KasirController::class, 'create'])->name('kasir.create');
    Route::post('/kasir/store', [KasirController::class, 'store'])->name('kasir.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/payment', function () {
        return view('payment.index');
    })->name('payment.index');
    
    Route::get('/payment/success', function () {
        return view('payment.success');
    })->name('payment.success');
    
    Route::get('/payment/pending', function () {
        return view('payment.pending');
    })->name('payment.pending');
    
    Route::get('/payment/failed', function () {
        return view('payment.failed');
    })->name('payment.failed');
});

// Midtrans notification
Route::post('/payment/notification', [KasirController::class, 'notification'])->name('payment.notification');
