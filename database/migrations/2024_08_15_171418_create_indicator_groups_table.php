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
        Schema::create('indicator_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // Mã code, tối đa 5 ký tự
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('creator_id')->constrained('users'); // Người tạo nhóm chỉ tiêu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicator_groups');
    }
};
