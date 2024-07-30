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
        Schema::create('tasks_document', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents');
            $table->string('task_code');
            $table->string('task_name');
            $table->string('reporting_cycle');
            $table->string('category');
            $table->string('required_result');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('creator');
            $table->enum('status', ['draft', 'assign']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_document');
    }
};
