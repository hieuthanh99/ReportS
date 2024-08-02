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
        Schema::create('task_result', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taskdocument_id');
            $table->foreignId('tasks_document_id')->constrained('tasks_document');
            $table->foreignId('document_id')->constrained('documents');
            $table->text('result');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_result');
    }
};
