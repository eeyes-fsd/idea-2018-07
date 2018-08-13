<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->can('manage_content'))
        {
            return true;
        }
    }
}