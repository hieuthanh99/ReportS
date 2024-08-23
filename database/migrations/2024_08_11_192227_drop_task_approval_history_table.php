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
        Schema::dropIfExists('task_approval_history');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('task_approval_history', function (Blueprint $table) {
            $table->id();
            // Các cột khác của bảng
            $table->timestamps();
        });
    }
};
