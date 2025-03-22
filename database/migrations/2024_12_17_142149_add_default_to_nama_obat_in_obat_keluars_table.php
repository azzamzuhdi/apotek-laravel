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
        $table->string('nama_obat')->default('Unknown')->change(); // Menambahkan default value 'Unknown'
    });
}

public function down()
{
    Schema::table('obat_keluars', function (Blueprint $table) {
        $table->string('nama_obat')->nullable(false)->change(); // Kembalikan ke kondisi semula
    });
}

};
