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
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'specialist_1', 
                'specialist_1_email', 
                'specialist_1_phone', 
                'specialist_2', 
                'specialist_2_email', 
                'specialist_2_phone'
            ]);
        });
    }

    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('specialist_1')->nullable();
            $table->string('specialist_1_email')->nullable();
            $table->string('specialist_1_phone')->nullable();
            $table->string('specialist_2')->nullable();
            $table->string('specialist_2_email')->nullable();
            $table->string('specialist_2_phone')->nullable();
        });
    }

};
