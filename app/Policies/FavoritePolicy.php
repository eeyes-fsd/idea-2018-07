<?php

namespace App\Policies;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavoritePolicy extends Policy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return null;
    }

    public function delete($user, Favorite $favorite)
    {
        return $favorite->author === $user;
    }
}
