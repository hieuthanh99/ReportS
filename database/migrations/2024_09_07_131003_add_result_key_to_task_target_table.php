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
        Schema::table('task_target', function (Blueprint $table) {
            $table->string('result_type',255)
                ->references('key')->on('master_work_result_types')
                ->after('request_results')->nullable()->default('TXT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_target', function (Blueprint $table) {
            //
        });
    }
};
