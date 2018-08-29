<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use App\Traits\GetUserTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;
    use GetUserTrait;

    public function before($user, $ability)
    {
        if ($user->can('manage_contents'))
        {
            return true;
        }
    }


}