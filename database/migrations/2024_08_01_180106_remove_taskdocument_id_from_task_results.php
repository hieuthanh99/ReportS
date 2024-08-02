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
            $table->dropColumn('taskdocument_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_result', function (Blueprint $table) {
            $table->unsignedBigInteger('taskdocument_id')->after('id');
        });
    }
};
