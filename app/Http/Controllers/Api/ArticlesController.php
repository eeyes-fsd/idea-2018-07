<?php

namespace App\Http\Controllers\Api;

use App\Fractal\CustomManager;
use App\Handlers\ImageUploadHandler;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\ShowArticlesRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
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
        $this->middleware('countView',['only' => 'show']);
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
        if ($request->get('author_type') === 'me') {
            $query = Article::ofAuthorByObject($this->getUserOrActiveOrganization());
        } else {
            $query = Article::ofAuthor($request->get('author_type'),$request->author_id);
        }

        $paginator = $query->orderBy($request->get('orderBy','id'),$request->getUser('orderMode','desc'))
                            ->ofCategory($request->get('category_id',0))
                            ->paginate($request->get('per_page',15));

        $articles = $paginator->getCollection();
        $manager = new CustomManager();
        $manager->setSerializer(new CustomSerializer());
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);
        $manager->parseIncludes(['children']);
        $resource = new Collection($articles, new ArticleTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $data = $manager->createData($resource)->setKey('articles')->toArray();

        return $data;

    }

    public function show(Article $article)
    {
        return $this->response->item($article, new ArticleTransformer(['info_for_cur'=>true]));
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
        foreach ($article->replies as $reply){
            $reply->delete();
        }
        foreach ($article->likes as $like) {
            $like->delete();
        }
        foreach ($article->favorites as $favorite) {
            $favorite->delete();
        }
        $article->delete();
        return $this->success('删除成功');
    }

    public function uploadImage(Request $request,ImageUploadHandler $uploader)
    {
        //todo e曈有个img.eeyes.net?
        $user = $this->getUserOrActiveOrganization();
        $user_type = $user instanceof User ? 'user' : 'organization';
        if ($request->image) {
            $result = $uploader->save($request->image, 'articles',$user_type.'_'.$user->id);
            if ($result) {
                $path = $result['path'];

                return $this->success(['url' => $path]);
            }
        }

        return $this->error('图片上传失败');
    }

    public function uploadCover(Article $article, Request $request, ImageUploadHandler $uploader)
    {
        $this->authorizeForUser($this->getUserOrActiveOrganization(), 'update', $article);
        if ($request->image) {
            $result = $uploader->save($request->image, 'covers', $article->id);
            if ($result) {
                $path = $result['path'];
                $article->update(['cover' => $path]);

                return $this->response->item($article, new ArticleTransformer());
            }
        }

        return $this->error('图片上传失败');
    }
}
