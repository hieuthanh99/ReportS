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
        Schema::create('history_change_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mapping_id');
            $table->integer('type_save')->nullable();
            $table->text('result');
            $table->text('description');
            $table->integer('number_cycle')->nullable();
            $table->integer('type_cycle')->nullable();
            $table->date('update_date')->nullable();
            $table->integer('update_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_change_document');
    }
};
