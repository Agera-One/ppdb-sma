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
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pendaftaran')->constrained('pendaftaran')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('jenis_berkas')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status_verifikasi', ['pending', 'valid', 'tidak_valid'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas');
    }
};
