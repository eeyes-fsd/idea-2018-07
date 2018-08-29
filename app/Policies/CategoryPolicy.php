<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        return $user->can('manage_categories');
    }

    public function update($user,Category $category)
    {
        return false;
    }

    public function delete($user,Category $category)
    {
        return false;
    }

    public function create($user)
    {
        return false;
    }
}

