<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->integer('stok_keluar')->default(0); // Menambahkan kolom stok_keluar
        });
    }

    public function down()
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn('stok_keluar'); // Menghapus kolom stok_keluar
        });
    }

};
