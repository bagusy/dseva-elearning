<?php

use App\Models\Course;
use App\Models\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('course_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Course::class);
            $table->foreignIdFor(Department::class);
            $table->string('subject');
            $table->text('message');
            $table->integer('day_completion');
            $table->dateTime('start_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_assignments');
    }
};
