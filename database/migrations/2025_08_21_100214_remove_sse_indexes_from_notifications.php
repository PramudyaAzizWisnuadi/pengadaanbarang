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
            // Remove some unnecessary indexes for SSE optimization
            // Keep only essential indexes for basic notification functionality
            $table->dropIndex('idx_type_created'); // Not needed for basic functionality
            $table->dropIndex('idx_user_created'); // Basic user_id index is sufficient
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Restore indexes if needed
            $table->index(['type', 'created_at'], 'idx_type_created');
            $table->index(['user_id', 'created_at'], 'idx_user_created');
        });
    }
};
