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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_siswa')->constrained('calon_siswa')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_jurusan')->constrained('jurusan')->restrictOnDelete()->cascadeOnUpdate();
            $table->dateTime('tanggal_daftar')->useCurrent();
            $table->enum('status', ['pending', 'menunggu_verifikasi', 'diterima', 'ditolak'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
