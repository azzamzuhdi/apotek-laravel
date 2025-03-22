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
        Schema::table('obat_keluars', function (Blueprint $table) {
            $table->integer('jumlah_keluar')->nullable(false)->change();  // Pastikan tidak nullable
        });
    }
    
    public function down()
    {
        Schema::table('obat_keluars', function (Blueprint $table) {
            $table->integer('jumlah_keluar')->nullable()->change();  // Kembalikan ke nullable jika perlu
        });
    }
    
};
