<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }
    }

    public function view(User $user, Video $video): bool
    {
        if ($video['category'] !== Video::CATEGORY_PRIVATE) {
            return true;
        }
        $companyOwnerId = optional($user->company)->user_id;
        return $companyOwnerId === $video['user_id'];
    }

    public function update(User $user, Video $video): bool
    {
        return $video['user_id'] === $user->getKey();
    }

    public function delete(User $user, Video $video): bool
    {
        return $video['user_id'] === $user->getKey();
    }
}
