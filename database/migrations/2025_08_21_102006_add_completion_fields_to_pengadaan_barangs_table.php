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
        Schema::table('pengadaan_barangs', function (Blueprint $table) {
            $table->timestamp('tanggal_selesai')->nullable()->after('tanggal_approval');
            $table->text('catatan_penyelesaian')->nullable()->after('catatan_approval');
            $table->string('foto_penyelesaian')->nullable()->after('foto_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengadaan_barangs', function (Blueprint $table) {
            $table->dropColumn(['tanggal_selesai', 'catatan_penyelesaian', 'foto_penyelesaian']);
        });
    }
};
