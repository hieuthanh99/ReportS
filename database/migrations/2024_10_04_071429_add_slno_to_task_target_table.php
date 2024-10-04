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
            $table->integer('slno')->nullable(); // Thêm cột slno
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_target', function (Blueprint $table) {
            $table->dropColumn('slno'); // Xóa cột slno khi rollback
        });
    }
};
