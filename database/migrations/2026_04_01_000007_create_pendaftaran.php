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
            $table->string('no_pendaftaran')->unique();
            $table->foreignId('id_siswa')
                  ->constrained('calon_siswa')
                  ->cascadeOnDelete();
            $table->foreignId('id_jurusan')
                  ->constrained('jurusan');
            $table->enum('status', [
                'pending',
                'menunggu_verifikasi',
                'diterima',
                'ditolak'
            ])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();
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
