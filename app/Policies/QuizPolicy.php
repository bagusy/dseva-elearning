<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }
    }

    public function view(User $user, Quiz $quiz): bool
    {
        if (!$quiz['is_custom']) {
            return true;
        }
        $companyOwnerId = optional($user->company)->user_id;
        return $companyOwnerId === $quiz['user_id'];
    }

    public function update(User $user, Quiz $quiz): bool
    {
        return $quiz['user_id'] === $user->getKey();
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        return $quiz['user_id'] === $user->getKey();
    }

    public function manageItems(User $user, Quiz $quiz): bool
    {
        return $quiz['user_id'] === $user->getKey();
    }
}
