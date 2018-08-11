<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use App\Transformers\OrganizationTransformer;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\CustomSerializer;

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

    public function update(Organization $organization, OrganizationRequest $request)
    {
        //
    }

    public function destroy(Organization $organization)
    {
        //
    }

    public function me()
    {
        $manager = new Manager();
        $manager->setSerializer(new CustomSerializer());

        $resource = new Item(Auth::guard('api_organization')->user(), new OrganizationTransformer());

        return $this->success(
            $manager->createData($resource)->toArray()
        );

//        return $this->response->item(Auth::guard('api_organization')->user(), new OrganizationTransformer());
    }
}
