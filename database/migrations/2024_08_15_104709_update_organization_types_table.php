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
        Schema::table('organization_types', function (Blueprint $table) {
            // Thêm cột code với unique index
            $table->string('code')->unique()->after('id');
            // Thêm cột description có thể null
            $table->text('description')->nullable()->after('type_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropColumn(['code', 'description']);
    }
};
