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
        Schema::create('barang_pengadaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengadaan_barang_id')->constrained()->onDelete('cascade');
            $table->foreignId('kategori_barang_id')->constrained()->onDelete('cascade');
            $table->string('nama_barang');
            $table->text('spesifikasi');
            $table->string('merk')->nullable();
            $table->integer('jumlah');
            $table->string('satuan');
            $table->decimal('harga_estimasi', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->text('keterangan')->nullable();
            $table->integer('prioritas')->default(1); // 1=rendah, 2=sedang, 3=tinggi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_pengadaans');
    }
};
