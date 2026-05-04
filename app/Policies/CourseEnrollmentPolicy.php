<?php

namespace App\Policies;

use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseEnrollmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }
    }

    public function view(User $user, CourseEnrollment $enrollment): bool
    {
        return $enrollment['user_id'] === $user->getKey();
    }

    public function update(User $user, CourseEnrollment $enrollment): bool
    {
        return $enrollment['user_id'] === $user->getKey();
    }
}
