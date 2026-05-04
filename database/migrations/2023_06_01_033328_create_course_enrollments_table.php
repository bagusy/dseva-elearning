<?php

use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\SubSection;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(CourseAssignment::class);
            $table->foreignIdFor(Course::class);
            $table->foreignIdFor(User::class);
            $table->dateTime('time_start');
            $table->dateTime('time_limit');
            $table->string('status');
            $table->string('certificate_no')->nullable();
            $table->foreignIdFor(SubSection::class)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_enrollments');
    }
};
