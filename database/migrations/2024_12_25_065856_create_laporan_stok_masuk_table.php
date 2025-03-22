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
    Schema::create('laporan_stok_masuk', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('obat_id');
        $table->integer('jumlah_masuk');
        $table->date('expired');
        $table->timestamps();

        $table->foreign('obat_id')->references('id')->on('obat')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_stok_masuk');
    }
};
