<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }
    }

    public function view(User $user, Course $course): bool
    {
        if (!$course['is_custom']) {
            return true;
        }
        return $this->isInSameCompany($user, $course['user_id']);
    }

    public function update(User $user, Course $course): bool
    {
        return $course['user_id'] === $user->getKey();
    }

    public function delete(User $user, Course $course): bool
    {
        return $course['user_id'] === $user->getKey();
    }

    public function manageContent(User $user, Course $course): bool
    {
        return $course['user_id'] === $user->getKey();
    }

    private function isInSameCompany(User $user, ?string $otherUserId): bool
    {
        if (is_null($otherUserId) || is_null($user['company_id'])) {
            return false;
        }
        $companyOwnerId = optional($user->company)->user_id;
        return $companyOwnerId === $otherUserId;
    }
}
