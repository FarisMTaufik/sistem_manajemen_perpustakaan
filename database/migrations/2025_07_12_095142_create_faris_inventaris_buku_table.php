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
        Schema::create('faris_inventaris_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faris_buku_id')->constrained('faris_buku')->onDelete('cascade');
            $table->date('tanggal_pemeriksaan');
            $table->enum('kondisi', ['baik', 'rusak', 'perlu_perbaikan'])->default('baik');
            $table->enum('status_inventaris', ['tersedia', 'dipinjam', 'dalam_perbaikan', 'hilang'])->default('tersedia');
            $table->string('lokasi_penyimpanan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('petugas')->nullable();
            $table->date('tanggal_perbaikan')->nullable();
            $table->date('tanggal_selesai_perbaikan')->nullable();
            $table->boolean('perlu_tindakan_lanjut')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faris_inventaris_buku');
    }
};
