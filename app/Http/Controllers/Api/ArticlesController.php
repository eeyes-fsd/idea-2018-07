<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
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
        if ((!$category = Category::find($request->category_id)) || $category->parent_id === 0 ) {
            return $this->error(500,'类别选择出错'); //默认不允许选择一级类别作为类别
        }
        //防止用户增加user_id或organization_id字段
        $article = Article::create($request->except('user_id','organization_id'));
        $transformer = new ArticleTransformer();
        return $this->success(201,'文章已创建', $transformer->transform($article));
    }

    public function update(Article $article, ArticleRequest $request)
    {
        //todo 之后迭代吧，管理员不应修改文章是否匿名
        $this->authorizeForUser($this->getUserOrActiveOrganization(),'update',$article);

        if ((!$category = Category::find($request->category_id)) || $category->parent_id === 0 ) {
            return $this->error(500,'类别选择出错'); //默认不允许选择一级类别作为类别
        }
        $article->update($request->except('user_id','organization_id'));
        $transformer = new ArticleTransformer();
        return $this->success(201,'文章已更新', $transformer->transform($article));
    }

    public function destroy(Article $article)
    {
        $this->authorizeForUser($this->getUserOrActiveOrganization(),'delete',$article);

        $article->delete();
        return $this->success("删除成功");
    }
}
