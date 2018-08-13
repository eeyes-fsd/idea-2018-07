<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a',['except' => ['index','show']]);
    }

    public function index()
    {
        //
    }

    public function show(Article $article)
    {
        //
    }

    public function store(ArticleRequest $request)
    {
        $article = Article::create($request->all());
        return $this->success(201,'文章已创建',$article->toArray());
    }

    public function update(Article $article, ArticleRequest $request)
    {
        $article = Article::update($request->all());
        return $this->success($article->toArray());
    }

    public function destroy(Article $article)
    {
        //
    }
}
