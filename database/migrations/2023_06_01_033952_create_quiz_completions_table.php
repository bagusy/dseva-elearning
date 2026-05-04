<?php

use App\Models\CourseEnrollment;
use App\Models\Quiz;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quiz_completions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(CourseEnrollment::class);
            $table->foreignIdFor(Quiz::class);
            $table->float('score', 20, 2);
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_completions');
    }
};
