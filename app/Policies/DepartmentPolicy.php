<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }
    }

    public function view(User $user, Department $department): bool
    {
        return $department['company_id'] === $user['company_id'];
    }

    public function update(User $user, Department $department): bool
    {
        return $department['company_id'] === $user['company_id']
            && $user->can('manage department');
    }

    public function delete(User $user, Department $department): bool
    {
        return $department['company_id'] === $user['company_id']
            && $user->can('manage department')
            && $department->employees()->count() === 0;
    }
}
