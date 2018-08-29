<?php

namespace App\Http\Middleware;

use Closure;

class CountView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $article = $request->route('article');
        $article->increment('view_count',1);
        return $next($request);
    }
}
