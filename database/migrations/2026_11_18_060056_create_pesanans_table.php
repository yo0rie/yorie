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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('nama_penerima');
            $table->string('no_hp');
            $table->text('alamat_pengiriman');
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('ongkir');
            $table->unsignedInteger('total');
            $table->enum('status', ['menunggu_pembayaran','menunggu_verifikasi','diproses','dikirim','selesai','dibatalkan'])
                  ->default('menunggu_pembayaran');
            $table->string('bukti_bayar')->nullable();
            $table->string('no_resi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
