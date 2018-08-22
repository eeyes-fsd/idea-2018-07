<?php

namespace App\Http\Controllers\Api;

use App\Fractal\CustomManager;
use App\Models\Article;
use App\Models\Organization;
use App\Models\User;
use App\Serializers\CustomSerializer;
use App\Traits\SearchTrait;
use App\Transformers\ArticleTransformer;
use App\Transformers\OrganizationTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;

class SearchController extends Controller
{
    public function searchUser(Request $request)
    {
        $per_page = $request->get('per_page',15);
        $key_word = $request->get('wd');

        $paginator = User::search($key_word,['username','nickname','signature'])
            ->orderByDate()
            ->paginate($per_page);
        $users = $paginator->getCollection();
        if($users->isEmpty()){
            return $this->success(404,'未搜索到用户',['未找到指定用户']);
        }
        $manager = new CustomManager();
        $manager->setSerializer(new CustomSerializer());
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);
        $resource = new Collection($users,new UserTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $data = $manager->createData($resource)->setKey('users')->toArray();

        return $data;
    }

    public function searchOrganization(Request $request)
    {
        $per_page = $request->get('per_page',15);
        $key_word = $request->get('wd');

        $paginator = Organization::search($key_word,['username','email','signature'])
            ->orderByDate()
            ->paginate($per_page);
        $organizations = $paginator->getCollection();
        if($organizations->isEmpty()){
            return $this->success(404,'未搜索到社团或组织',['未搜索到社团或组织']);
        }
        $manager = new CustomManager();
        $manager->setSerializer(new CustomSerializer());
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);
        $resource = new Collection($organizations,new OrganizationTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $data = $manager->createData($resource)->setKey('organizations')->toArray();

        return $data;
    }

    public function searchArticle(Request $request)
    {
        $per_page = $request->get('per_page',15);
        $key_word = $request->get('wd');

        $paginator = Article::search($key_word,['title','body'])
            ->orderByDate()
            ->paginate($per_page);
        $articles = $paginator->getCollection();
        if($articles->isEmpty()){
            return $this->success(404,'未搜索到相关文章',['未搜索到相关文章']);
        }
        $manager = new CustomManager();
        $manager->setSerializer(new CustomSerializer());
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);
        $resource = new Collection($articles,new ArticleTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $data = $manager->createData($resource)->setKey('articles')->toArray();

        return $data;
    }
}
