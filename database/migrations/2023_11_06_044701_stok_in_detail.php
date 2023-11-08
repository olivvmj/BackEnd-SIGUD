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
        Schema::create('stock_in_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('stock_in_id');
            $table->string('serial_number');
            $table->string('serial_number_manufaktur');
            $table->string('status');
            $table->timestamps();

            $table->foreign('barang_id')->references('id')->on('barang');
            $table->foreign('stock_in_id')->references('id')->on('stock_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_in_detail');
    }
};
