<?php

namespace App\Console\Commands;

use App\Jobs\AssignTraining;
use App\Models\CourseAssignment;
use Illuminate\Console\Command;

class AssignCourse extends Command
{
    protected $signature = 'assign:course';

    public function handle()
    {
        $courseAssignments = CourseAssignment::where('is_expired', 0)->get();
        foreach ($courseAssignments as $courseAssignment) {
            $deadline = $courseAssignment['start_date']->copy()->addDays($courseAssignment['day_completion']);
            if ($deadline < now()) {
                $courseAssignment['is_expired'] = 1;
                $courseAssignment->save();
                continue;
            }
            if ($courseAssignment['start_date'] > now()) {
                continue;
            }
            if ($courseAssignment['is_assigned']) {
                continue;
            }
            dispatch(new AssignTraining($courseAssignment));
        }
    }
}
