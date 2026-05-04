<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage company',
            'manage department',
            'manage employee',
            'manage subscription',
            'create quiz',
            'delete quiz',
            'create course',
            'delete course',
            'create video',
            'delete video',
            'assign course',
            'do training',
            'phishing simulation',
            'manage user',
        ];
        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => User::ROLE_ADMIN, 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $subscriptionManager = Role::firstOrCreate(['name' => User::ROLE_SUBSCRIPTION_MANAGER, 'guard_name' => 'web']);
        $subscriptionManager->syncPermissions([
            'manage subscription',
            'manage user',
        ]);

        $courseCreator = Role::firstOrCreate(['name' => User::ROLE_COURSE_CREATOR, 'guard_name' => 'web']);
        $courseCreator->syncPermissions([
            'create quiz',
            'delete quiz',
            'create course',
            'delete course',
        ]);

        $contentCreator = Role::firstOrCreate(['name' => User::ROLE_CONTENT_CREATOR, 'guard_name' => 'web']);
        $contentCreator->syncPermissions([
            'create video',
            'delete video',
        ]);

        $userAdmin = Role::firstOrCreate(['name' => User::ROLE_USER_ADMIN, 'guard_name' => 'web']);
        $userAdmin->syncPermissions([
            'manage company',
            'manage department',
            'manage employee',
            'manage subscription',
            'create quiz',
            'delete quiz',
            'create course',
            'delete course',
            'create video',
            'delete video',
            'assign course',
            'do training',
            'phishing simulation',
        ]);

        $userEmployee = Role::firstOrCreate(['name' => User::ROLE_USER_EMPLOYEE, 'guard_name' => 'web']);
        $userEmployee->syncPermissions([
            'do training',
        ]);
    }
}
