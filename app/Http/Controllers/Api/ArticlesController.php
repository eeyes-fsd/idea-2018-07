<?php

namespace App\Http\Controllers\Api;

use App\Fractal\CustomManager;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\ShowArticlesRequest;
use App\Models\Article;
use App\Models\Category;
use App\Serializers\CustomSerializer;
use App\Transformers\ArticleTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use function PHPSTORM_META\type;
use Tymon\JWTAuth\Claims\Custom;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a',['except' => ['index','show']]);
    }

    /**
     * 筛选显示，可以指定：
     * order、author、category、per_page
     *
     * @param ShowArticlesRequest $request
     * @return mixed
     */
    public function index(ShowArticlesRequest $request)
    {
        $manager = new CustomManager();
        $manager->setSerializer(new CustomSerializer());

        $paginator = Article::orderBy($request->get('orderBy','id'),$request->getUser('orderMode','desc'))
                            ->ofAuthor($request->get('author_type'),$request->get('author_id'))
                            ->ofCategory($request->get('category_id',0))
                            ->paginate($request->get('per_page',15));
        $articles = $paginator->getCollection();
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);

        $resource = new Collection($articles, new ArticleTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $data = $manager->createData($resource)->setKey('articles')->toArray();

        return $data;



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
        //todo maintainer shouldn't change whether the article is anonymous, leave it for next version
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
