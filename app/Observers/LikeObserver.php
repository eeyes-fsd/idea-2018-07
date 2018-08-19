<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-17
 * Time: 12:13
 */

namespace App\Observers;

use App\Models\Favorite;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeObserver
{
    public function creating(Like $like)
    {

        if (Auth::guard('api_user')->check()) {
            $like->user_id = Auth::guard('api_user')->id();
        } elseif (Auth::guard('api_organization')->check() && Auth::guard('api_organization')->user()->active) {
            $like->organization_id = Auth::guard('api_organization')->id();
        } else {
            throw new \Exception();
        }

        if ($like->article_id) {
            $like->article->increment('like_count',1);
        } elseif ($like->reply_id) {
            $like->reply->increment('like_count',1);
        }

    }

    public function deleting(Like $like)
    {
        if ($like->article_id) {
            $like->article->decrement('like_count',1);
        } elseif ($like->reply_id) {
            $like->reply->decrement('like_count',1);
        }
    }

}