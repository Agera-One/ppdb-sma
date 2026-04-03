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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pendaftaran')
                  ->unique()
                  ->constrained('pendaftaran')
                  ->cascadeOnDelete();
            $table->decimal('jumlah_tagihan', 12, 2)->default(0);
            $table->decimal('jumlah_bayar', 12, 2)->nullable();
            $table->enum('status_pembayaran', [
                'belum_bayar',
                'dp',
                'lunas'
            ])->default('belum_bayar');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->text('catatan_pembayaran')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
