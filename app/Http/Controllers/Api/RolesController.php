<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function assignUserFounder(User $user)
    {
        //todo
        $user->assignRole('Founder');
    }

    public function assignUserMaintainer(User $user)
    {
        $user->assignRole('maintainer');
    }

    public function assignOrganizationFounder(Organization $organization)
    {
        
    }
}
