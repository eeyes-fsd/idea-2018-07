<?php

namespace App\Transformers;

use App\Models\Organization;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;

class OrganizationTransformer extends TransformerAbstract
{
    public function transform(Organization $organization)
    {


        $data = [
            'id' => $organization->id,
            'username' => $organization->username,
            'email' => $organization->email,
            'profile_photo' => $organization->profile_photo,
            'active' => $organization->active,
            'email' => $organization->email_visibility ? $organization->email : '***',
            'qq' => $organization->qq_visibility ? $organization->qq : '***',
            'email_visibility' => $organization->email_visibility,
            'qq_visibility' => $organization->qq_visibility,
        ];

        if(Auth::guard('api_organization')->user() === $organization)
        {
            $data['email'] = $organization->email;
            $data['qq'] = $organization->qq;
        }

        return $data;
    }
}