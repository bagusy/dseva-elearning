<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CourseAssignment;
use App\Models\CourseEnrollment;
use App\Models\Department;
use App\Models\Employee;
use App\Models\QuizCompletion;
use App\Models\QuizCompletionAnswer;
use App\Models\CourseEnrollmentProgress;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeDemoUsers extends Command
{
    protected $signature = 'dseva:purge-demo-users
        {--force : Skip confirmation}
        {--demo-domain=dseva.test : Email domain that marks demo accounts}
        {--demo-company=Dseva Demo Co : Name of the demo company to remove}';

    protected $description = 'Hard-delete demo users (and their company / departments / employee rows) created by DemoUsersSeeder.';

    public function handle(): int
    {
        if (app()->environment('production')) {
            $this->error('Refusing to run in production.');
            return self::FAILURE;
        }

        $domain = $this->option('demo-domain');
        $companyName = $this->option('demo-company');

        $userIds = User::withTrashed()
            ->where('email', 'like', '%@' . $domain)
            ->pluck('id')
            ->all();

        $company = Company::where('name', $companyName)->first();

        if (empty($userIds) && is_null($company)) {
            $this->info('Nothing to purge.');
            return self::SUCCESS;
        }

        $this->line('About to hard-delete:');
        $this->line('  - ' . count($userIds) . ' user(s) matching @' . $domain);
        if ($company) {
            $this->line('  - company "' . $companyName . '" with ' . $company->departments()->withTrashed()->count() . ' department(s)');
            $this->line('  - any employees, course assignments, enrollments, quiz completions tied to that company / those users');
        }

        if (!$this->option('force') && !$this->confirm('Proceed?', false)) {
            $this->warn('Aborted.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($userIds, $company) {
            $companyId = optional($company)->id;

            // Identify the assignments and enrollments we need to clean up.
            $assignmentIds = CourseAssignment::query()
                ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                ->pluck('id')
                ->all();

            $enrollmentIds = CourseEnrollment::query()
                ->when(!empty($userIds), fn ($q) => $q->whereIn('user_id', $userIds))
                ->when(!empty($assignmentIds), fn ($q) => $q->orWhereIn('course_assignment_id', $assignmentIds))
                ->pluck('id')
                ->all();

            // Cascade from the deepest leaves upward.
            if (!empty($enrollmentIds)) {
                $completionIds = QuizCompletion::whereIn('course_enrollment_id', $enrollmentIds)->pluck('id')->all();
                if (!empty($completionIds)) {
                    QuizCompletionAnswer::whereIn('quiz_completion_id', $completionIds)->delete();
                    QuizCompletion::whereIn('id', $completionIds)->delete();
                }
                CourseEnrollmentProgress::whereIn('course_enrollment_id', $enrollmentIds)->delete();
                CourseEnrollment::whereIn('id', $enrollmentIds)->delete();
            }

            if (!empty($assignmentIds)) {
                CourseAssignment::whereIn('id', $assignmentIds)->delete();
            }

            // Employees (soft-deleted) → force delete, scoped both ways.
            Employee::withTrashed()
                ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                ->orWhereIn('user_id', $userIds ?: ['__none__'])
                ->forceDelete();

            // Departments (soft-deleted) → force delete by company.
            if ($companyId) {
                Department::withTrashed()->where('company_id', $companyId)->forceDelete();
            }

            // Company → hard delete (no soft delete on Company).
            if ($company) {
                Company::where('id', $company->id)->delete();
            }

            // Users last (force delete triggers cleanup of model_has_roles/permissions via User::booted()).
            foreach (User::withTrashed()->whereIn('id', $userIds)->get() as $user) {
                $user->forceDelete();
            }
        });

        // Remove the generated credentials file if present.
        $credPath = base_path('DEMO_CREDENTIALS.md');
        if (file_exists($credPath)) {
            @unlink($credPath);
            $this->line('Removed DEMO_CREDENTIALS.md');
        }

        $this->info('Demo users purged.');
        return self::SUCCESS;
    }
}
