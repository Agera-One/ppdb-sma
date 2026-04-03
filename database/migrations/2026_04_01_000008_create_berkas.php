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
            $table->foreignId('id_pendaftaran')
                  ->constrained('pendaftaran')
                  ->cascadeOnDelete();
            $table->string('jenis_berkas');
            $table->string('nama_file');
            $table->string('path_file');                        
            $table->enum('status_berkas', [
                'belum_diverifikasi',
                'valid',
                'tidak_valid'
            ])->default('belum_diverifikasi');
            $table->text('catatan_berkas')->nullable();
            $table->timestamps();
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
