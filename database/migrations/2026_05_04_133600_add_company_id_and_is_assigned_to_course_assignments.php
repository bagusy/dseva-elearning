<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('course_assignments')) {
            return;
        }

        Schema::table('course_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('course_assignments', 'company_id')) {
                $table->uuid('company_id')->nullable()->after('department_id');
                $table->index('company_id');
            }
            if (!Schema::hasColumn('course_assignments', 'is_assigned')) {
                $table->boolean('is_assigned')->default(0)->after('is_expired');
            }
        });

        // Backfill company_id from department.company_id for existing rows.
        if (Schema::hasColumn('course_assignments', 'company_id')) {
            DB::table('course_assignments')
                ->whereNull('company_id')
                ->orderBy('id')
                ->chunkById(500, function ($rows) {
                    foreach ($rows as $row) {
                        $department = DB::table('departments')->where('id', $row->department_id)->first();
                        if ($department && isset($department->company_id)) {
                            DB::table('course_assignments')
                                ->where('id', $row->id)
                                ->update(['company_id' => $department->company_id]);
                        }
                    }
                });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('course_assignments')) {
            return;
        }
        Schema::table('course_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('course_assignments', 'is_assigned')) {
                $table->dropColumn('is_assigned');
            }
            if (Schema::hasColumn('course_assignments', 'company_id')) {
                $table->dropIndex(['company_id']);
                $table->dropColumn('company_id');
            }
        });
    }
};
