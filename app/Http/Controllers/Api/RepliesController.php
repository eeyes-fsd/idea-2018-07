<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ReplyRequest;
use App\Models\Article;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a',['except' => ['index','show']]);
    }

    /**
     * 获取某文章的全部回复
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        //
    }

    public function show(Reply $reply)
    {
        //
    }

    public function store(ReplyRequest $request)
    {
        Reply::create($request->only(['body','article_id','reply_id']));
        //todo should return article that includes replies

    }

    public function update(Reply $reply, ReplyRequest $request)
    {
        //
    }

    public function destroy(Article $article)
    {
        //
    }
}
