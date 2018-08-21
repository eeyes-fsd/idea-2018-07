<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy extends Policy
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
        return $this->isUserEqual($user, $reply->author);
    }
}
