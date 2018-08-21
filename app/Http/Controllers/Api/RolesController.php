<?php

namespace App\Http\Controllers\Api;

use App\Models\Organization;
use App\Models\User;
use App\Transformers\OrganizationTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function assignUserFounder(User $user)
    {
        if(User::count() == 1 && $this->getUserOrActiveOrganization() instanceof User) {
            $this->getUserOrActiveOrganization()->assignRole('founder');

            return $this->response->item($this->getUserOrActiveOrganization(), new UserTransformer());
        } elseif ($this->getUserOrActiveOrganization()->can('manage_users')) {
            $user->assignRole('founder');

            return $this->response->item($user, new UserTransformer());
        } else {
            return $this->error(403,'权限不足');
        }
    }

    public function assignUserMaintainer(User $user)
    {
        if ($this->getUserOrActiveOrganization()->can('manage_users')) {
            $user->assignRole('maintainer');

            return $this->response->item($user, new UserTransformer());
        } else {
            return $this->error(403,'权限不足');
        }
    }

    public function assignOrganizationFounder(Organization $organization)
    {
        if(Organization::count() == 1 && $this->getUserOrActiveOrganization() instanceof Organization) {
            $this->getUserOrActiveOrganization()->assignRole('founder');

            return $this->response->item($this->getUserOrActiveOrganization(), new OrganizationTransformer());
        } elseif ($this->getUserOrActiveOrganization()->can('manage_users')) {
            $organization->assignRole('founder');

            return $this->response->item($organization, new OrganizationTransformer());
        } else {
            return $this->error(403,'权限不足');
        }
    }
}
