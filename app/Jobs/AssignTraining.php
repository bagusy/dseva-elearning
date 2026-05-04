<?php

namespace App\Jobs;

use App\Models\CourseAssignment;
use App\Models\CourseEnrollment;
use App\Models\User;
use App\Notifications\EmailInvitation;
use App\Notifications\NewCourse;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AssignTraining implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private CourseAssignment $courseAssignment;

    public function __construct(CourseAssignment $courseAssignment)
    {
        $courseAssignment->load(['department.employees.user', 'department.company.user', 'course']);
        $this->courseAssignment = $courseAssignment;
    }

    public function handle()
    {
        $employees = $this->courseAssignment->department->employees;
        $company = $this->courseAssignment->department->company;
        $course = $this->courseAssignment->course;
        $courseEnrollmentUsers = CourseEnrollment::where('course_assignment_id', $this->courseAssignment['id'])->pluck('user_id')->toArray();

        $allEnrolled = true;
        foreach ($employees as $employee) {
            $user = $employee->user;
            if (is_null($user)) {
                $user = User::firstOrCreate(
                    ['email' => $employee['email']],
                    ['password' => Hash::make(uniqid())]
                );
                if (is_null($user['company_id'])) {
                    $user['company_id'] = $company['id'];
                    $user->save();
                }
                if (!$user->hasAnyRole(User::ROLE_LIST) && !$user->hasRole(User::ROLE_USER_EMPLOYEE)) {
                    $user->assignRole(User::ROLE_USER_EMPLOYEE);
                }

                $token = Str::random(64);

                DB::table('password_resets')->where('email', '=', $employee['email'])->delete();
                DB::table('password_resets')->insert([
                    'email' => $employee['email'],
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now()
                ]);

                $user->notify(new EmailInvitation($company['user']['name'], $token));

                $employee['user_id'] = $user['id'];
                $employee->save();
            }

            if (!in_array($user['id'], $courseEnrollmentUsers)) {
                $startDate = $this->courseAssignment['start_date'] instanceof Carbon
                    ? $this->courseAssignment['start_date']->copy()
                    : Carbon::parse($this->courseAssignment['start_date']);
                $cE = new CourseEnrollment;
                $cE['course_assignment_id'] = $this->courseAssignment['id'];
                $cE['course_id'] = $this->courseAssignment['course_id'];
                $cE['user_id'] = $user['id'];
                $cE['time_start'] = $startDate->copy();
                $cE['time_limit'] = $startDate->copy()->addDays($this->courseAssignment['day_completion']);
                $cE['status'] = CourseEnrollment::STATUS_NEW;
                $cE->save();

                $user->notify(new NewCourse($course, $cE['time_start'], $cE['time_limit'],$this->courseAssignment['subject'], $this->courseAssignment['message']));
            }
            if (is_null($employee['user_id'])) {
                $allEnrolled = false;
            }
        }

        if ($allEnrolled) {
            $this->courseAssignment['is_assigned'] = true;
            $this->courseAssignment->save();
        }
    }
}
