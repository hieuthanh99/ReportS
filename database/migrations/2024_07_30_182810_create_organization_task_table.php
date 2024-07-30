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
        Schema::create('organization_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tasks_document_id')->constrained('tasks_document');
            $table->foreignId('document_id')->constrained('documents');
            $table->foreignId('organization_id')->constrained('organizations');
            $table->string('creator');
            $table->foreignId('users_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_task');
    }
};
