<?php

namespace App\Policies;

use App\Http\Requests\OrganizationRequest;
use App\Models\Article;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy extends Policy
{
    use HandlesAuthorization;

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
        return $currentUser->email === $user->email;
    }

    public function updateArticle(Organization $organization, Article $article)
    {
        return $article->user_id === $organization->id || $organization->can('manage_contents');
    }

}
