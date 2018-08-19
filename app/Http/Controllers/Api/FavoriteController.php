<?php

namespace App\Http\Controllers\Api;

use App\Fractal\CustomManager;
use App\Http\Requests\FavoriteRequest;
use App\Models\Favorite;
use App\Models\Organization;
use App\Models\User;
use App\Serializers\CustomSerializer;
use App\Transformers\ArticleTransformer;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;

class FavoriteController extends Controller
{

    public function index(FavoriteRequest $request)
    {
        //todo we should add favorite time in return
        if ($request->author_type === 'me') {
            $paginator = $this->getUserOrActiveOrganization()->favoriteArticles()->paginate($request->get('per_page',15));
        } elseif ($request->author_type === 'user') {
            $paginator = User::find($request->author_id)->favoriteArticles()->paginate($request->get('per_page',15));
        } else {
            $paginator = Organization::find($request->author_id)->favoriteArticles()->paginate($request->get('per_page',15));
        }

        $manager = new CustomManager();
        $manager->setSerializer(new CustomSerializer());

        $articles = $paginator->getCollection();
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);

        $resource = new Collection($articles, new ArticleTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $data = $manager->createData($resource)->setKey('articles')->toArray();

        return $data;
    }

    public function storeOrDestroy(Request $request)
    {
        if (!$request->article_id) {
            return $this->error(500,'缺少article_id参数');
        }
        $favorite = Favorite::where('article_id',$request->article_id)
                        ->ofAuthorByObject($this->getUserOrActiveOrganization())
                        ->first();
        if ($favorite){
            $favorite->delete();
            return $this->success(200,'取消收藏成功',['取消收藏成功']);
        } else {
            Favorite::create(['article_id' => $request->article_id]);
            return $this->success(201,'收藏成功',['收藏成功']);
        }

    }

}
