<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi_sementara', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('subtotal');
            $table->string('payment_method')->default('cash')->after('order_id');
            $table->string('snap_token')->nullable()->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('transaksi_sementara', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'payment_method', 'snap_token']);
        });
    }
}; 