<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-17
 * Time: 12:13
 */

namespace App\Observers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteObserver
{
    public function creating(Favorite $favorite)
    {
        if (Auth::guard('api_user')->check()) {
            $favorite->user_id = Auth::guard('api_user')->id();
        } elseif (Auth::guard('api_organization')->check() && Auth::guard('api_organization')->user()->active) {
            $favorite->organization_id = Auth::guard('api_organization')->id();
        } else {
            throw new \Exception();
        }

        $favorite->article->increment('favorite_count',1);
    }

    public function deleting(Favorite $favorite)
    {
        $favorite->article->decrement('favorite_count',1);
    }

}