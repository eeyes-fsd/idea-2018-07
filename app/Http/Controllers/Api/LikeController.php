<?php

namespace App\Http\Controllers\Api;

use App\Fractal\CustomManager;
use App\Models\Like;
use App\Models\Reply;
use App\Serializers\CustomSerializer;
use App\Transformers\ArticleTransformer;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;

class LikeController extends Controller
{

    public function index(Request $request)
    {
        //todo should return time
        if ($request->input('type','article') === 'article') {
            $paginator = $this->getUserOrActiveOrganization()->likedArticles()->paginate($request->get('per_page',15));
            $manager = new CustomManager();
            $manager->setSerializer(new CustomSerializer());

            $articles = $paginator->getCollection();
            $queryParams = array_diff_key($_GET, array_flip(['page']));
            $paginator->appends($queryParams);


            $resource = new Collection($articles, new ArticleTransformer());
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
            $data = $manager->createData($resource)->setKey('articles')->toArray();

            return $data;
        } else {
            $paginator = $this->getUserOrActiveOrganization()->likedReplies()->paginate($request->get('per_page',15));
            $manager = new CustomManager();
            $manager->setSerializer(new CustomSerializer());

            $replies = $paginator->getCollection();
            $queryParams = array_diff_key($_GET, array_flip(['page']));
            $paginator->appends($queryParams);

            $resource = new Collection($replies,new ReplyTransformer());
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
            $data = $manager->createData($resource)->setKey('replies')->toArray();

            return $data;
        }

    }
    
    public function storeOrDestroy(Request $request)
    {
        if (!$request->article_id  && !$request->reply_id) {
            return $this->error(500,'缺少article_id或reply_id参数');
        }
        if ($request->article_id) {
            $like = Like::where('article_id',$request->article_id)
                ->ofAuthorByObject($this->getUserOrActiveOrganization())
                ->first();
            if ($like){
                $like->delete();
                return $this->success(200,'取消点赞成功',['取消点赞成功']);
            } else {
                Like::create(['article_id' => $request->article_id]);
                return $this->success(201,'点赞成功',['点赞成功']);
            }
        } elseif ($request->reply_id) {
            $like = Like::where('reply_id', $request->reply_id)
                ->ofAuthorByObject($this->getUserOrActiveOrganization())
                ->first();
            if ($like) {
                $like->delete();
                return $this->success(200,'取消点赞成功',['取消点赞成功']);
            } else {
                Like::create(['reply_id' => $request->reply_id]);
                return $this->success(201,'点赞成功',['点赞成功']);
            }
        }

    }
}
