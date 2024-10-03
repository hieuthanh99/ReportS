<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('task_target', function (Blueprint $table) {
            $table->string('unit')->nullable(); // Cột đơn vị
            $table->string('target_type')->nullable(); // Cột loại chỉ tiêu
            $table->string('task_type')->nullable(); // Cột loại nhiệm vụ
            $table->string('target')->nullable(); // Cột chỉ tiêu
        });
    }

    public function down()
    {
        Schema::table('task_target', function (Blueprint $table) {
            $table->dropColumn('unit'); // Xóa cột đơn vị
            $table->dropColumn('target_type'); // Xóa cột loại chỉ tiêu
            $table->dropColumn('task_type'); // Xóa cột loại nhiệm vụ
            $table->dropColumn('target'); // Xóa cột chỉ tiêu
        });
    }
};
