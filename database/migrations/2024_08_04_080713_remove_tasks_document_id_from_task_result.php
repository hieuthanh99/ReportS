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
            $table->dropForeign(['tasks_document_id']);
            $table->dropColumn('tasks_document_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_result', function (Blueprint $table) {
            $table->foreignId('tasks_document_id')->constrained('tasks_document');
        });
    }
};
