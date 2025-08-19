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
        Schema::table('barang_pengadaans', function (Blueprint $table) {
            $table->dropColumn('alasan_pengadaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_pengadaans', function (Blueprint $table) {
            $table->text('alasan_pengadaan')->nullable()->after('keterangan');
        });
    }
};
