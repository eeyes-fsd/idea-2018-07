<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\Article;
use App\Models\Favorite;
use App\Models\Like;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dingo\Api\Http\Response;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('api.a',['except'=>['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //todo permission and pagination
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * Update the specified resource in storage.
     * name 和 username 不允许修改
     *
     * @param UserRequest $request
     * @param ImageUploadHandler $uploader
     * @param User $user
     * @return Response
     * @throws \Exception
     */
    public function update(User $user, UserRequest $request, ImageUploadHandler $uploader)
    {
        $this->authorizeForUser($this->getUserOrActiveOrganization(),'update',$user);
//        $this->authorizeForUser(Auth::guard('api_user')->user(),'update',$user);

        //基本信息更改
        $data = $request->all();

        //头像更改  这个保留在这吧，万一以后用呢
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatar', $user->id);
            if ($result) {
                $data['avatar'] = $result['path'];
                //删除旧头像，避免头像文件堆积
                $uploader->delete($user->avatar);
            }
        }

        //更新数据
        $user->update($data);
        return $this->response->item($user, new UserTransformer());
    }

    public function updateAvatar(User $user, Request $request,ImageUploadHandler $uploader)
    {
        $this->authorizeForUser($this->getUserOrActiveOrganization(),'update',$user);
        $data = [];
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatar', $user->id);
            if ($result) {
                $data['avatar'] = $result['path'];
                //删除旧头像，避免头像文件堆积
                $uploader->delete($user->avatar);
            }
        }
        $user->update($data);
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorizeForUser($this->getUserOrActiveOrganization(),'delete', $user);
        foreach ($user->articles as $article)
        {
            $article->delete();
        }
        $user->delete();
        return $this->success("删除成功");
    }

    /**
     * 返回当前用户
     * @return Response
     */
    public function me()
    {
        return $this->response->item(Auth::guard('api_user')->user(), new UserTransformer());
    }


    public function currentInfo(Request $request)
    {
        $user = $this->getUserOrActiveOrganization();
        $user_type = $user instanceof User ? 'user' : 'organization';
        $like_query = Like::where("{$user_type}_id",$user->id);
        if ($request->article_id) {
            $like_query = $like_query->where('article_id',$request->article_id);
            $favorite_query = Favorite::where("{$user_type}_id",$user->id)
                            ->where('article_id',$request->article_id);

            return $this->success([
                'liked' => $like_query->count(),
                'favorited' => $favorite_query->count(),
            ]);
        } elseif ($request->reply_id) {
            $like_query = $like_query->where('reply_id',$request->reply_id);

            return $this->success([
                'liked' => $like_query->count(),
            ]);
        } else {
            return $this->error('参数错误');
        }
    }
}
