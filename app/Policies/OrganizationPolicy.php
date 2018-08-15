<?php

namespace App\Policies;

use App\Models\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->can('manage_users'))
        {
            return true;
        }
    }

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(Organization $currentUser, Organization $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * 注销组织
     *
     * @param Organization $currentOrganization
     * @param Organization $organization
     * @return bool
     */
    public function delete(Organization $currentOrganization, Organization $organization)
    {
        return $currentOrganization->id === $organization->id;
    }
}
