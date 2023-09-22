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
        Schema::create('obat_terjual', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('obat_id');
            $table->integer('jumlah');
            $table->integer('jumlah_harga');
            $table->timestamps();

            // Definisi foreign key constraint
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('obat_id')->references('id')->on('obats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_terjual');
    }
};
