<?php

namespace App\Traits;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait GetUserTrait
{
    public function getUserOrActiveOrganization()
    {
        if (Auth::guard('api_user')->check()) {
            return Auth::guard('api_user')->user();
        } elseif (Auth::guard('api_organization')->check() && Auth::guard('api_organization')->user()->active) {
            return Auth::guard('api_organization')->user();
        } else {
            return null;
        }
    }

    public function isUserEqual($user1, $user2)
    {
        if ($user1 instanceof User && $user2 instanceof User) {
            return $user1->username === $user2->username;
        } elseif ($user1 instanceof Organization && $user2 instanceof Organization) {
            return $user1->email === $user2->email;
        } else {
            return false;
        }
    }
}