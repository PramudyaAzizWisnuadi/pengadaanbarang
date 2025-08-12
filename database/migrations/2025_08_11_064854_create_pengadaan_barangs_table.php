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
        Schema::create('pengadaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengadaan')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_pemohon');
            $table->string('jabatan');
            $table->string('departemen');
            $table->text('alasan_pengadaan');
            $table->decimal('total_estimasi', 15, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'completed'])->default('draft');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_dibutuhkan');
            $table->timestamp('tanggal_approval')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_approval')->nullable();
            $table->string('file_ttd_atasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan_barangs');
    }
};
