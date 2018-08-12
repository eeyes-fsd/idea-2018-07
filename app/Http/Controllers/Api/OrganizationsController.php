<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use App\Transformers\OrganizationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Response\CustomResponse;

class OrganizationsController extends Controller
{
    public function index()
    {
        //
    }

    public function show(Organization $organization)
    {
        //
    }

    public function store(OrganizationRequest $request)
    {
        $origanization = Organization::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return $this->response->array([
            'status_code' => 200,
            'message' => '社团用户创建成功'
        ]);
    }

    /**
     * @param Request $request
     * @param ImageUploadHandler $uploader
     * @param int $id
     * @return \Dingo\Api\Http\Response|void
     * @throws \Exception
     */
    public function update(Request $request,ImageUploadHandler $uploader, $id)
    {
        $organization = Organization::find($id);
        if(Auth::guard('api_organization')->user()->cant('update',$organization))
        {
            return $this->error(403,'权限不足');
        }

        //基本信息更改
        $data = [
            'username' => $request->input('username',$organization->username),
            'signature' => $request->input('signature',$organization->signature),
            'qq' => $request->input('qq',$organization->qq),
            'email_visibility' => $request->input('email_visibility',$organization->email_visibility),
            'qq_visibility' => $request->input('qq_visibility',$organization->qq_visibility),
        ];

        //头像更改
        if ($request->profile_photo) {
            $result = $uploader->save($request->profile_photo, 'org_profile_photo', $organization->id);
            if ($result) {
                $data['profile_photo'] = $result['path'];
            }
        }

        //修改密码
        if($request->password){
            //todo 密码约束
            if($organization->password === bcrypt('default_password')){
                $data['password'] = bcrypt($request->password);
            }else{
                if(bcrypt($request->old_password === $organization->password)){
                    $data['password'] = bcrypt($request->password);
                }else{
                    return $this->error('旧密码错误');
                }
            }
        }

        try {
            $organization->update($data);
        }catch (\Exception $e){
            throw $e;
        }

        return $this->success($organization->toArray());
    }

    public function destroy(Organization $organization)
    {
        //
    }

    public function me()
    {
        return $this->response->item(Auth::guard('api_organization')->user(), new OrganizationTransformer());
    }
}
