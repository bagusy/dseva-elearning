<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }
    }

    public function view(User $user, Employee $employee): bool
    {
        return $employee['company_id'] === $user['company_id'];
    }

    public function update(User $user, Employee $employee): bool
    {
        return $employee['company_id'] === $user['company_id']
            && $user->can('manage employee');
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $employee['company_id'] === $user['company_id']
            && $user->can('manage employee');
    }
}
