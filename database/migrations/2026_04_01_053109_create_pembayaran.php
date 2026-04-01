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
            $table->foreignId('id_pendaftaran')->unique()->constrained('pendaftaran')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('tanggal_bayar')->useCurrent();
            $table->decimal('jumlah', 10, 2);
            $table->enum('status', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar');
            $table->string('metode')->nullable();
            $table->string('bukti_bayar')->nullable();
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
