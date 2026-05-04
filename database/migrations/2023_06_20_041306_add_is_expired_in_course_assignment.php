<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('course_assignments', function (Blueprint $table) {
            $table->boolean('is_expired')->default(0);
        });
    }

    public function down()
    {
        Schema::table('course_assignments', function (Blueprint $table) {
            $table->dropColumn(['is_expired']);
        });
    }
};
