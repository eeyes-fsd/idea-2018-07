<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-12
 * Time: 17:39
 */

namespace App\Observers;

use \App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleObserver
{
    public function creating(Article $article)
    {
        if(Auth::guard('api_user')->chece()){
            $article->user_id = Auth::guard('api_user')->id();
        }elseif(Auth::guard('api_organization')->check()){
            $article->organization_id = Auth::guard('api_organization')->id();
        }
    }
}