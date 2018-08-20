<?php

namespace App\Traits;

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
}