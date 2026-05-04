<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->unsignedInteger('index')->default(0);
        });
        Schema::table('sub_sections', function (Blueprint $table) {
            $table->unsignedInteger('index')->default(0);
        });
    }

    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('index');
        });
        Schema::table('sub_sections', function (Blueprint $table) {
            $table->dropColumn('index');
        });
    }
};
