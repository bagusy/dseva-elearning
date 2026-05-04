<?php

use App\Models\Quiz;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quiz_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Quiz::class);
            $table->text('question');
            $table->json('answer');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_items');
    }
};
