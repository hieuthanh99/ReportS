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
        Schema::table('tasks_document', function (Blueprint $table) {
            $table->string('progress')->nullable()->after('status'); // Adjust 'existing_column' as needed
            $table->text('progress_evaluation')->nullable()->after('progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks_document', function (Blueprint $table) {
            $table->dropColumn('progress');
            $table->dropColumn('progress_evaluation');
        });
    }
};
