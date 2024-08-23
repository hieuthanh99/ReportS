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
        Schema::table('organizations', function (Blueprint $table) {
            // Thay đổi độ dài của mã cơ quan thành 5 ký tự
            $table->string('code', 5)->change();
        
            $table->string('address')->nullable(); // Địa chỉ
            $table->string('website')->nullable(); // Địa chỉ Website
            $table->string('specialist_1')->nullable(); // Cán bộ chuyên trách 1
            $table->string('specialist_1_email')->nullable(); // email cán bộ chuyên trách 1
            $table->string('specialist_1_phone')->nullable(); // Số điện thoại cán bộ chuyên trách 1
            $table->string('specialist_2')->nullable(); // Cán bộ chuyên trách 2
            $table->string('specialist_2_email')->nullable(); // email cán bộ chuyên trách 2
            $table->string('specialist_2_phone')->nullable(); // Số điện thoại cán bộ chuyên trách 2
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
           // Khôi phục độ dài ban đầu của mã cơ quan
           $table->string('code', 255)->change();

           // Xóa các trường đã thêm
           $table->dropColumn([
               'address',
               'website',
               'specialist_1',
               'specialist_1_email',
               'specialist_1_phone',
               'specialist_2',
               'specialist_2_email',
               'specialist_2_phone',
           ]);
        });
    }
};
