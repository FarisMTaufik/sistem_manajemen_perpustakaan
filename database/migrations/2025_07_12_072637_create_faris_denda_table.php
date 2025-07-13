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
        Schema::create('faris_denda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faris_peminjaman_id')->unique()->constrained('faris_peminjaman')->onDelete('cascade');
            $table->decimal('jumlah_denda', 10, 2);
            $table->enum('status_pembayaran', ['belum_dibayar', 'dibayar'])->default('belum_dibayar');
            $table->date('tanggal_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faris_denda');
    }
};
