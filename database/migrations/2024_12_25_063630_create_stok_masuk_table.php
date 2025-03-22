<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('stok_masuk', function (Blueprint $table) {
        $table->id();
        $table->string('nama_obat'); // Nama obat
        $table->integer('jumlah'); // Jumlah stok masuk
        $table->decimal('harga_beli', 10, 2); // Harga beli per unit
        $table->date('tanggal_masuk'); // Tanggal stok masuk
        $table->decimal('total_harga', 15, 2); // Total harga (jumlah x harga_beli)
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuk');
    }
};
