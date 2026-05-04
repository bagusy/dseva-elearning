<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (empty($email)) {
            $this->command->warn('AdminUserSeeder skipped: ADMIN_EMAIL not set in .env');
            return;
        }

        $generatedPassword = null;
        if (empty($password)) {
            $password = bin2hex(random_bytes(12));
            $generatedPassword = $password;
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'status' => User::STATUS_ACTIVE,
            ]
        );

        $user->syncRoles([User::ROLE_ADMIN]);

        $this->command->info('ID:     ' . $user->id);
        $this->command->info('Email:  ' . $user->email);
        $this->command->info('Name:   ' . $user->name);
        $this->command->info('Roles:  ' . $user->getRoleNames()->implode(', '));
        $this->command->info('Status: ' . $user->status);
        if ($generatedPassword !== null) {
            $this->command->warn('Generated password (shown once): ' . $generatedPassword);
        }
    }
}
