<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Quiz;
use App\Models\Video;
use App\Policies\CourseEnrollmentPolicy;
use App\Policies\CoursePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\QuizPolicy;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        CourseEnrollment::class => CourseEnrollmentPolicy::class,
        Department::class => DepartmentPolicy::class,
        Employee::class => EmployeePolicy::class,
        Quiz::class => QuizPolicy::class,
        Video::class => VideoPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
