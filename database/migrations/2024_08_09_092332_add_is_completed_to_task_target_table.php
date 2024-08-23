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
            $table->boolean('is_completed')->default(false); // Thêm trường boolean với giá trị mặc định là false
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_target', function (Blueprint $table) {
            $table->dropColumn('is_completed'); // Xóa trường khi rollback migration
        });
    }
};
