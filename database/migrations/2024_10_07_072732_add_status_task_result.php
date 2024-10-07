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
            $table->enum('status', ['new', 'assign', 'reject', 'complete',  'staff_complete', 'sub_admin_complete'])->default('new'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_result', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
