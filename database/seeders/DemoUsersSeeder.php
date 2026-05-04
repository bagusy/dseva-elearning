<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Seed one demo user per role for development / testing.
     *
     * NOT for production use. All accounts share the same default password
     * "Password1!" so anyone with the README can log in. Run only on local
     * or dedicated demo environments.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->error('DemoUsersSeeder refuses to run in production.');
            return;
        }

        $defaultPassword = 'Password1!';

        // Platform-level roles — no company affiliation required.
        $platformRoles = [
            User::ROLE_ADMIN => 'admin@dseva.test',
            User::ROLE_SUBSCRIPTION_MANAGER => 'subscription@dseva.test',
            User::ROLE_COURSE_CREATOR => 'course@dseva.test',
            User::ROLE_CONTENT_CREATOR => 'content@dseva.test',
        ];

        foreach ($platformRoles as $role => $email) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Demo ' . User::roleString($role),
                    'password' => Hash::make($defaultPassword),
                    'avatar' => '/dashboard/assets/images/user/avatar-1.jpg',
                    'email_verified_at' => now(),
                ]
            );
            $user['status'] = User::STATUS_ACTIVE;
            $user->save();
            $user->syncRoles([$role]);
            $this->command->info("Demo user: {$email}  /  {$defaultPassword}  ({$role})");
        }

        // Tenant USER_ADMIN owns a demo company.
        $tenantAdmin = User::firstOrCreate(
            ['email' => 'tenant@dseva.test'],
            [
                'name' => 'Demo Tenant Admin',
                'password' => Hash::make($defaultPassword),
                'avatar' => '/dashboard/assets/images/user/avatar-2.jpg',
                'email_verified_at' => now(),
            ]
        );
        $tenantAdmin['status'] = User::STATUS_ACTIVE;
        $tenantAdmin->save();
        $tenantAdmin->syncRoles([User::ROLE_USER_ADMIN]);

        $company = Company::firstOrCreate(
            ['name' => 'Dseva Demo Co'],
            [
                'industry' => 'Software & Comp. Serv.',
                'size' => 'Small',
                'user_id' => $tenantAdmin['id'],
            ]
        );

        $tenantAdmin['company_id'] = $company['id'];
        $tenantAdmin->save();

        // Seed default departments for the demo company if absent.
        foreach (Department::DEFAULT_LIST as $departmentName) {
            $dept = Department::where('company_id', $company['id'])
                ->where('name', $departmentName)
                ->first();
            if (is_null($dept)) {
                $dept = new Department;
                $dept['name'] = $departmentName;
                $dept['company_id'] = $company['id'];
                $dept->save();
            }
        }
        $itDept = Department::where('company_id', $company['id'])
            ->where('name', 'IT')
            ->first();

        // USER_EMPLOYEE inside the demo company, attached to IT.
        $employeeUser = User::firstOrCreate(
            ['email' => 'employee@dseva.test'],
            [
                'name' => 'Demo Employee',
                'password' => Hash::make($defaultPassword),
                'avatar' => '/dashboard/assets/images/user/avatar-3.jpg',
                'email_verified_at' => now(),
            ]
        );
        $employeeUser['status'] = User::STATUS_ACTIVE;
        $employeeUser['company_id'] = $company['id'];
        $employeeUser->save();
        $employeeUser->syncRoles([User::ROLE_USER_EMPLOYEE]);

        $employee = Employee::where('email', $employeeUser['email'])->first();
        if (is_null($employee)) {
            $employee = new Employee;
            $employee['email'] = $employeeUser['email'];
            $employee['company_id'] = $company['id'];
            $employee['department_id'] = $itDept['id'];
            $employee['user_id'] = $employeeUser['id'];
            $employee->save();
        }

        $this->command->info("Demo user: {$tenantAdmin['email']}  /  {$defaultPassword}  (USER_ADMIN, owns Dseva Demo Co)");
        $this->command->info("Demo user: {$employeeUser['email']}  /  {$defaultPassword}  (USER_EMPLOYEE in Dseva Demo Co / IT)");

        $this->writeCredentialsFile($defaultPassword);
    }

    private function writeCredentialsFile(string $password): void
    {
        $rows = [
            ['Email', 'Role', 'Scope'],
            ['---', '---', '---'],
            ['admin@dseva.test', 'ADMIN', 'Platform super-admin (bypasses all policies)'],
            ['subscription@dseva.test', 'SUBSCRIPTION_MANAGER', 'Platform billing / user management'],
            ['course@dseva.test', 'COURSE_CREATOR', 'Platform course + quiz library'],
            ['content@dseva.test', 'CONTENT_CREATOR', 'Platform video library (non-private)'],
            ['tenant@dseva.test', 'USER_ADMIN', 'Owns "Dseva Demo Co" (with default departments)'],
            ['employee@dseva.test', 'USER_EMPLOYEE', 'In "Dseva Demo Co" / IT department'],
        ];

        $lines = [];
        $lines[] = '# Demo Credentials (LOCAL / DEV ONLY)';
        $lines[] = '';
        $lines[] = 'This file is auto-generated by `DemoUsersSeeder` and is `.gitignore`d.';
        $lines[] = 'It must never be committed or shared. Run `php artisan dseva:purge-demo-users`';
        $lines[] = 'to remove the demo data and this file.';
        $lines[] = '';
        $lines[] = 'Password for all six accounts: `' . $password . '`';
        $lines[] = '';
        foreach ($rows as $i => $row) {
            $lines[] = '| ' . implode(' | ', $row) . ' |';
        }
        $lines[] = '';
        $lines[] = 'Generated at ' . now()->toDateTimeString() . '.';

        $path = base_path('DEMO_CREDENTIALS.md');
        file_put_contents($path, implode("\n", $lines) . "\n");
        $this->command->info('Wrote ' . $path);
    }
}
