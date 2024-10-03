<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('task_target', function (Blueprint $table) {
        $table->text('request_results_task')->nullable(); // Thêm cột kết quả yêu cầu nhiệm vụ
        $table->text('results_task')->nullable(); // Thêm cột kết quả yêu cầu nhiệm vụ
    });
}

public function down()
{
    Schema::table('task_target', function (Blueprint $table) {
        $table->dropColumn('request_results_task'); // Để rollback nếu cần
        $table->dropColumn('results_task'); // Thêm cột kết quả yêu cầu nhiệm vụ
    });
}

};
