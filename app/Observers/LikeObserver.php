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
use App\Notifications\ArticleLiked;
use App\Notifications\ReplyLiked;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    }

    public function created(Like $like)
    {
        $threshold = 3;
        if ($like->article_id) {
            $like->article->author->notify(new ArticleLiked($like));

            $notifications = $like->article->author->getNotifications(['type'=>'article_liked'])->take($threshold)->get();
            foreach ($notifications as $notification) {
                if ($notification->data['like_id'] == $like->id) {
                    $like->notification_id = $notification->id;
                    $like->save();
                    break;
                }
            }

            $like->article->increment('like_count',1);

        } elseif ($like->reply_id) {
            $like->reply->author->notify(new ReplyLiked($like));

            $notifications = $like->article->author->getNotifications(['type'=>'reply_liked'])->take($threshold)->get();
            foreach ($notifications as $notification) {
                if ($notification->data['like_id'] == $like->id) {
                    $like->notification_id = $notification->id;
                    $like->save();
                    break;
                }
            }

            $like->reply->increment('like_count',1);
        }
    }

    public function deleted(Like $like)
    {
        if ($like->article_id) {
            $like->article->decrement('like_count',1);
        } elseif ($like->reply_id) {
            $like->reply->decrement('like_count',1);
        }
        DB::table('notifications')->where('id', $like->notification_id)->delete();
    }

}