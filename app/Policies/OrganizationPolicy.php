<?php

namespace App\Policies;

use App\Models\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy extends Policy
{
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
}
