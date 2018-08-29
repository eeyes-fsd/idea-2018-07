<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->can('manage_users'))
        {
            return true;
        }
    }

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * 注销用户
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function delete(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }
}
