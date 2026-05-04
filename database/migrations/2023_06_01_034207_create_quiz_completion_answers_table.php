<?php

use App\Models\QuizCompletion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quiz_completion_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(QuizCompletion::class);
            $table->string('question');
            $table->string('answer');
            $table->boolean('answer_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_completion_answers');
    }
};
