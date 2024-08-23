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
        Schema::create('task_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();
            $table->string('name'); // Tên nhóm công việc
            $table->text('description')->nullable(); // Mô tả nhóm công việc
            $table->foreignId('creator_id')->constrained('users'); // Người tạo nhóm công việc (liên kết với bảng users)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_groups');
    }
};
