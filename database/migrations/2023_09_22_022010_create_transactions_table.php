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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code_transaction')->nullable();
            $table->string('kasir')->nullable();
            $table->bigInteger('jumlah_obat')->nullable();
            $table->bigInteger('jumlah_harga')->nullable();
            $table->bigInteger('jumlah_bayar')->nullable();
            $table->bigInteger('jumlah_kembalian')->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
