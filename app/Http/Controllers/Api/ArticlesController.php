<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Transformers\ArticleTransformer;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a',['except' => ['index','show']]);
    }

    public function index()
    {
        //return $this->response->collection(Article::all(), new );
        //TODO 处理 Collection 的转换器
    }

    public function show(Article $article)
    {
        return $this->response->item($article, new ArticleTransformer());
    }

    public function store(ArticleRequest $request)
    {
        $article = Article::create($request->all());
        $transformer = new ArticleTransformer();
        return $this->success(201,'文章已创建', $transformer->transform($article));
    }

    public function update(Article $article, ArticleRequest $request)
    {
        $article->update($request->all());
        $transformer = new ArticleTransformer();
        return $this->success(201,'文章已更新', $transformer->transform($article));
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return $this->success("删除成功");
    }
}
