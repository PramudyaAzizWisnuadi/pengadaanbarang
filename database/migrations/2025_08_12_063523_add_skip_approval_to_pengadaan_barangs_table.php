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
            $table->boolean('skip_approval')->default(false)->after('status');
            $table->text('alasan_skip_approval')->nullable()->after('skip_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengadaan_barangs', function (Blueprint $table) {
            $table->dropColumn(['skip_approval', 'alasan_skip_approval']);
        });
    }
};
