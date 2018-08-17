<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        //todo there should be a permission named manage_replies
        if ($user->can('manage_contents')) {
            return true;
        }
        return null;
    }

    public function delete($user, Reply $reply)
    {
        return $user === $reply->author;
    }
}
