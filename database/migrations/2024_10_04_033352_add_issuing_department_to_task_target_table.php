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
            $table->string('issuing_organization_id')->nullable(); // Thêm cột issuing_department
        });
    }
    
    public function down()
    {
        Schema::table('task_target', function (Blueprint $table) {
            $table->dropColumn('issuing_organization_id'); // Xóa cột nếu rollback
        });
    }
};
