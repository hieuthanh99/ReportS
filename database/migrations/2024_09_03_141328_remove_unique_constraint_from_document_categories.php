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
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropUnique('document_categories_code_unique');
        });
    }

    public function down()
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->unique('code');
        });
    }
};
