<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;

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
}
