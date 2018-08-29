<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-12
 * Time: 17:39
 */

namespace App\Observers;

use \App\Models\Article;
use App\Traits\GetUserTrait;
use Illuminate\Support\Facades\Auth;

class ArticleObserver
{
    use GetUserTrait;

    public function creating(Article $article)
    {
        if (Auth::guard('api_user')->check()) {
            $article->user_id = Auth::guard('api_user')->id();
        } elseif (Auth::guard('api_organization')->check() && Auth::guard('api_organization')->user()->active) {
            $article->organization_id = Auth::guard('api_organization')->id();
        } else {
            throw new \Exception();
        }
    }

    public function created(Article $article)
    {
        if (!$user = $this->getUserOrActiveOrganization()) {
            throw new \Exception();
        } else {
            $user->increment('article_count',1);
        }
    }

    public function deleted(Article $article)
    {
        $article->author->decrement('article_count',1);
    }

    public function saving(Article $article)
    {
        $oldArticle = Article::find($article->id);
        if ($oldArticle) {
            if ($article->category_id !== $oldArticle->category_id) {
                $oldArticle->category->parent->decrement('article_count', 1);
                $oldArticle->category->decrement('article_count', 1);
                $article->category->parent->increment('article_count', 1);
                $article->category->increment('article_count', 1);
            }
        } else {
            $article->category->parent->increment('article_count',1);
            $article->category->increment('article_count',1);
        }
    }
}