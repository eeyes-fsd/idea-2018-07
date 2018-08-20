<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-17
 * Time: 12:13
 */

namespace App\Observers;


use App\Models\Reply;
use App\Notifications\ArticleReplied;
use App\Notifications\ReplyReplied;
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
    }

    public function created(Reply $reply)
    {
        $reply->article->author->notify(new ArticleReplied($reply));
        if ($reply->hasParentReply()) {
            $reply->parentReply->author->notify(new ReplyReplied($reply));
        }
        $reply->article->increment('reply_count',1);
    }

}