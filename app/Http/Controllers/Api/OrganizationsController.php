<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use App\Transformers\OrganizationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a',['except' => ['show','store']]);
    }

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
        if ($organization->active || $this->authorizeForUser($this->getUserOrActiveOrganization(),'show',$organization)) {
            return $this->response->item($organization, new OrganizationTransformer());
        } else {
            $this->error(404,'未找到该用户或该用户尚未通过审核');
        }
    }

    public function store(OrganizationRequest $request)
    {
        $organization = Organization::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => config('app.url') . '/storage/images/default_avatar.jpg',
        ]);

        $transformer = new OrganizationTransformer();
        return $this->success(201,"社团用户创建成功，请联系管理员审核",$transformer->transform($organization));
    }

    /**
     * @param OrganizationRequest $request
     * @param ImageUploadHandler $uploader
     * @param Organization $organization
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function update(Organization $organization,OrganizationRequest $request,ImageUploadHandler $uploader)
    {
        $this->authorizeForUser($this->getUserOrOrganization(),'update',$organization);

       //基本信息更改
        $data = $request->except(['active','email']);

        //头像更改
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'org_avatar', $organization->id);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        //修改密码
        if($request->password){
            //TODO reset password
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

    public function updateAvatar(Organization $organization, Request $request,ImageUploadHandler $uploader)
    {
        $this->authorizeForUser($this->getUserOrOrganization(),'update',$organization);
        $data = [];
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'org_avatar', $organization->id);
            if ($result) {
                $data['avatar'] = $result['path'];
                //删除旧头像，避免头像文件堆积
                $uploader->delete($organization->avatar);
            }
        }
        $organization->update($data);
        return $this->response->item($organization, new OrganizationTransformer());
    }

    /**
     * @param Organization $organization
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Organization $organization)
    {
        $this->authorizeForUser($this->getUserOrOrganization(),'delete',$organization);
        $organization->articles->delete();
        foreach ($organization->articles as $article)
        {
            $article->delete();
        }

        return $this->success("社团用户删除成功");
    }

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function me()
    {
        return $this->response->item(Auth::guard('api_organization')->user(), new OrganizationTransformer());
    }

    public function activate(Organization $organization)
    {
        if ($this->getUserOrActiveOrganization()->can('manage_users')) {
            $organization->update(['active' => true]);
        } else {
            $this->error(403,'权限不足');
        }

        $transformer = new OrganizationTransformer();
        return $this->success(200,$organization->username.'审核通过', $transformer->transform($organization));
    }
}
