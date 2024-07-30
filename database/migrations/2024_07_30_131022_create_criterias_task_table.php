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
        Schema::create('criterias_task', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing 'ID' column
            $table->integer('TaskID')->nullable();
            $table->integer('CriteriaID')->nullable();
            $table->string('CriteriaCode', 255)->nullable();
            $table->string('CriteriaName', 255)->nullable();
            $table->string('CreatedBy', 256)->nullable();
            $table->string('UpdatedBy', 256)->nullable();
            $table->integer('DocumentID')->nullable();
            $table->string('TaskCode', 50)->nullable();
            $table->text('RequestResult')->nullable();
            $table->timestamps(); // This will add 'created_at' and 'updated_at' columns

            // If you want to add foreign keys, you can do it here
            // $table->foreign('TaskID')->references('id')->on('tasks');
            // $table->foreign('CriteriaID')->references('id')->on('criterias');
            // $table->foreign('DocumentID')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterias_task');
    }
};
