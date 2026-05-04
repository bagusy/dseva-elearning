<?php

namespace App\Console\Commands;

use App\Models\SubSection;
use Illuminate\Console\Command;

class FixCourse extends Command
{
    protected $signature = 'fix:course';

    public function handle()
    {
        $subSections = SubSection::where('type',SubSection::TYPE_VIDEO)->get();
        foreach ($subSections as $subSection) {
            if (is_null($subSection->video)){
                $subSection->delete();
            }
        }
    }
}
