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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Admin yang akan menerima notifikasi
            $table->unsignedBigInteger('pengadaan_id'); // Pengadaan yang terkait
            $table->unsignedBigInteger('created_by'); // User yang membuat pengadaan
            $table->string('type'); // 'pengajuan_baru', 'status_update', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Data tambahan dalam format JSON
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pengadaan_id')->references('id')->on('pengadaan_barangs')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->index(['user_id', 'is_read']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
