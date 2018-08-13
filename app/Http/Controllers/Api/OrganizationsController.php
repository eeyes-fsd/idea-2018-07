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

    /**
     * @param Organization $organization
     * @return \Dingo\Api\Http\Response
     */
    public function show(Organization $organization)
    {
        return $this->response->item($organization, new OrganizationTransformer());
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
     * @param OrganizationRequest $request
     * @param ImageUploadHandler $uploader
     * @param Organization $organization
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function update(Organization $organization, $request,ImageUploadHandler $uploader)
    {
       //基本信息更改
        $data = $request->all();

        //头像更改
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'org_avatar', $organization->id);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        //修改密码
        if($request->password){
            //TODO 密码约束
            if($organization->password === bcrypt('default_password') || bcrypt($request->old_password) === $organization->password)
            {
                $data['password'] = bcrypt($request->password);
            }
        }

        try {
            $organization->update($data);
        }catch (\Exception $e){
            throw $e;
        }

        return $this->response->item($organization, new OrganizationTransformer());
    }

    /**
     * @param Organization $organization
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Organization $organization)
    {
        $this->authorize('delete',$organization);
        $organization->delete();
        return $this->success("社团用户删除成功");
    }

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function me()
    {
        return $this->response->item(Auth::guard('api_organization')->user(), new OrganizationTransformer());
    }
}
