<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-17
 * Time: 12:13
 */

namespace App\Observers;


use App\Models\Reply;
use Illuminate\Support\Facades\Auth;

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        if (Auth::guard('api_user')->check()) {
            $reply->user_id = Auth::guard('api_user')->id();
        } elseif (Auth::guard('api_organization')->check() && Auth::guard('api_organization')->user()->active) {
            $reply->organization_id = Auth::guard('api_organization')->id();
        } else {
            throw new \Exception();
        }

        $reply->article->increment('reply_count',1);
    }

}