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
        Schema::create('faris_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_anggota')->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->string('nomor_telepon');
            $table->string('email')->unique();
            $table->date('tanggal_lahir');
            $table->enum('jenis_anggota', ['mahasiswa', 'dosen', 'umum']);
            $table->enum('status', ['aktif', 'nonaktif', 'diblokir'])->default('aktif');
            $table->date('tanggal_bergabung');
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faris_anggota');
    }
};
