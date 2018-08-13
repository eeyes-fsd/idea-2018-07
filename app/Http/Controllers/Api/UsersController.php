<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Support\Facades\Auth;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Response\CustomResponse;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return $this->success(200,$user->toArray());
    }

    /**
     * Update the specified resource in storage.
     * name和username 不允许修改
     *
     * @param Request $request
     * @param ImageUploadHandler $uploader
     * @param $id
     * @return Response|void
     * @throws \Exception
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, $id)
    {
        $user = User::find($id);

        //基本信息更改
        $data = [
            'nickname' => $request->input('nickname',$user->nickname),
            'email' => $request->input('email',$user->email),
            'signature' => $request->input('signature',$user->signature),
            'phone' => $request->input('phone',$user->phone),
            'qq' => $request->input('qq',$user->qq),
            'phone_visibility' => $request->input('phone_visibility',$user->phone_visibility),
            'email_visibility' => $request->input('email_visibility',$user->email_visibility),
            'qq_visibility' => $request->input('qq_visibility',$user->qq_visibility),
        ];

        //头像更改
        if ($request->profile_photo) {
            $result = $uploader->save($request->profile_photo, 'profile_photo', $user->id);
            if ($result) {
                $data['profile_photo'] = $result['path'];
            }
        }

        //修改密码
        if($request->password){
            //todo 密码检查？
            if($user->password === bcrypt('default_password')){
                $data['password'] = bcrypt($request->password);
            }else{
//                if(bcrypt($request->old_password === $user->password)){
//                    $data['password'] = bcrypt($request->password);
//                }
                $data['password'] = bcrypt($request->password);
            }
        }

        try {
            $user->update($data);
        }catch (\Exception $e){
            throw $e;
        }

        return $this->success($user->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function me()
    {
        return $this->response->item(Auth::guard('api_user')->user(), new UserTransformer());
    }
}
