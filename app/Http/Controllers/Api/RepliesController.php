<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ReplyRequest;
use App\Models\Article;
use App\Models\Reply;
use App\Serializers\CustomSerializer;
use App\Transformers\ArticleTransformer;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a',['except' => ['index','show']]);
    }

    /**
     * 返回某文章的全部留言
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        if ((!$request->article_id) || (!$article = Article::findOrFail($request->article_id))) {
            $this->error(404, '缺少或错误参数article_id');
        }
        $manager = new Manager();
        $manager->setSerializer(new CustomSerializer());
        $resources = new Collection($article->replies,new ReplyTransformer());
        return $manager->createData($resources)->toArray();
    }

    public function show(Reply $reply)
    {
        //
    }

    public function store(ReplyRequest $request)
    {
        if (Article::findOrFail($request->article_id)) {
            if ($request->reply_id) {
                Reply::findOrFail($request->reply_id);
            }
        }
        $reply = Reply::create($request->only(['body','article_id','reply_id']));
        return $this->response->item($reply,new ReplyTransformer());
    }

    public function update(Reply $reply, ReplyRequest $request)
    {
        //我认为不应该有这个功能
        return $this->error(403,'对不起目前还没有更改留言的功能');
    }

    public function destroy(Reply $reply)
    {
        $this->authorizeForUser($this->getUserOrActiveOrganization(),'delete',$reply);
        //俺觉得这样删除可以保留别人的评论。
        //或者再把user_id和organization_id置成指定用户也行。如果置零的话会导致$reply->author出错
        $reply->body = '该评论已被删除';
        $reply->update(['body' => '该评论已被删除']);
        return $this->success(200,'删除成功',[]);
    }
}
