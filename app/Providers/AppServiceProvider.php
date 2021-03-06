<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Like;
use App\Models\Reply;
use App\Observers\ArticleObserver;
use App\Observers\CategoryObserver;
use App\Observers\FavoriteObserver;
use App\Observers\LikeObserver;
use App\Observers\ReplyObserver;
use App\Serializers\CustomSerializer;
use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Article::observe(ArticleObserver::class);
        \Carbon\Carbon::setLocale('zh');
        Reply::observe(ReplyObserver::class);
        Favorite::observe(FavoriteObserver::class);
        Like::observe(LikeObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
