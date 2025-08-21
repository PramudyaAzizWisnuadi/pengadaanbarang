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
        Schema::table('notifications', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index(['user_id', 'is_read'], 'idx_user_read');
            $table->index(['user_id', 'created_at'], 'idx_user_created');
            $table->index(['type', 'created_at'], 'idx_type_created');
            $table->index(['pengadaan_id'], 'idx_pengadaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_user_read');
            $table->dropIndex('idx_user_created');
            $table->dropIndex('idx_type_created');
            $table->dropIndex('idx_pengadaan');
        });
    }
};
