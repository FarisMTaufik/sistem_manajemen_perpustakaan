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
        Schema::create('faris_buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->string('isbn')->nullable()->unique();
            $table->foreignId('faris_kategori_id')->constrained('faris_kategori')->onDelete('cascade');
            $table->integer('jumlah_salinan')->default(1);
            $table->integer('jumlah_tersedia')->default(1);
            $table->enum('kondisi', ['baik', 'rusak', 'perlu_perbaikan'])->default('baik');
            $table->text('deskripsi')->nullable();
            $table->string('gambar_sampul')->nullable();
            $table->date('tanggal_inventaris')->nullable();
            $table->text('catatan_inventaris')->nullable();
            $table->enum('status_inventaris', ['tersedia', 'dipinjam', 'dalam_perbaikan', 'hilang'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faris_buku');
    }
};
