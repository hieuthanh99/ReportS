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
        Schema::table('task_result', function (Blueprint $table) {
            $table->integer('number_type')->nullable(); // Adjust type if needed
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_result', function (Blueprint $table) {
            $table->dropColumn(['number_type', 'type']);
        });
    }
};
