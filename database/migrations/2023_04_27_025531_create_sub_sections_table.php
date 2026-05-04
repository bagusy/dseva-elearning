<?php

use App\Models\Quiz;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\Video;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sub_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Section::class);
            $table->string('type')->default(SubSection::TYPE_VIDEO);
            $table->foreignIdFor(Video::class)->nullable();
            $table->foreignIdFor(Quiz::class)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_sections');
    }
};
