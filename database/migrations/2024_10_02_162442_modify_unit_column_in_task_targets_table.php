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
            // Đổi cột 'unit' thành kiểu unsignedBigInteger
            $table->unsignedBigInteger('unit')->nullable()->change();
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
            // Đổi lại cột 'unit' thành kiểu string
            $table->string('unit')->nullable()->change();
        });
    }
};
