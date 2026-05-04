<?php

use App\Models\SubSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course_enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('course_enrollments', 'sub_section_id')) {
                $table->foreignIdFor(SubSection::class)->nullable();
            }
            if (Schema::hasColumn('course_enrollments', 'App\Models\SubSection')) {
                $table->dropColumn('App\Models\SubSection');
            }
        });
    }

    public function down()
    {
        // No-op: the create migration now produces the correct sub_section_id column,
        // so reversing this alter would only corrupt the schema on already-fixed DBs.
    }
};
