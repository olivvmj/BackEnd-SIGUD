<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permintaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('stok_id');
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('status_permintaan_id');
            $table->date('tanggal_permintaan');
            $table->string('jumlah_barang');
            $table->string('alamat_barang');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('stok_id')->references('id')->on('stock');
            $table->foreign('barang_id')->references('id')->on('barang');
            $table->foreign('status_permintaan_id')->references('id')->on('status_permintaan');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan');
    }
};
