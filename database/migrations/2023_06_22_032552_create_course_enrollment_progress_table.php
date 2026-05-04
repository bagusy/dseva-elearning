<?php

use App\Models\CourseEnrollment;
use App\Models\Quiz;
use App\Models\Video;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_enrollment_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('index');
            $table->foreignIdFor(CourseEnrollment::class);
            $table->string('type');
            $table->foreignIdFor(Video::class)->nullable();
            $table->foreignIdFor(Quiz::class)->nullable();
            $table->boolean('is_done')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_enrollment_progress');
    }
};
