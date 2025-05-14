<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi_final', function (Blueprint $table) {
            $table->string('payment_method')->after('kembalian');
            $table->string('order_id')->nullable()->after('payment_method');
            $table->string('transaction_status')->nullable()->after('order_id');
            $table->string('transaction_id')->nullable()->after('transaction_status');
        });
    }

    public function down()
    {
        Schema::table('transaksi_final', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'order_id', 'transaction_status', 'transaction_id']);
        });
    }
}; 