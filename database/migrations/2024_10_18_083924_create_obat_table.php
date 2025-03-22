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
        Schema::create('obat', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis
            $table->string('nama_obat'); // Kolom untuk nama obat
            $table->decimal('harga_beli', 10, 2); // Kolom untuk harga beli
            $table->decimal('harga_jual', 10, 2); // Kolom untuk harga jual
            $table->integer('stok'); // Kolom untuk stok
            $table->timestamps(); // Kolom untuk created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('obat'); // Menghapus tabel jika migration dibatalkan
    }
};
