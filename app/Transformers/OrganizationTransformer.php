<?php

namespace App\Transformers;

use App\Models\Organization;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;

class OrganizationTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['articles'];

    protected $includeArticleCount;

    public function __construct($includeArticleCount = 3)
    {
        $this->includeArticleCount = $includeArticleCount;
    }

    public function transform(Organization $organization)
    {
        $data = [
            'id' => $organization->id,
            'username' => $organization->username,
            'avatar' => $organization->avatar,
            'signature' => $organization->signature,
            'active' => $organization->active,
            'email' => $organization->email_visibility ? $organization->email : '***',
            'qq' => $organization->qq_visibility ? $organization->qq : '***',
            'article_count' => $organization->article_count,
            'email_visibility' => $organization->email_visibility,
            'qq_visibility' => $organization->qq_visibility,
        ];

        if(Auth::guard('api_organization')->check()
            && Auth::guard('api_organization')->user()->email === $organization->email)
        {
            $data['email'] = $organization->email;
            $data['qq'] = $organization->qq;
            $data['notification_count'] = $organization->notification_count;
        }

        return $data;
    }

    public function includeArticles(Organization $organization)
    {
        return $this->collection($organization->articles()->take($this->includeArticleCount)->get(),(new ArticleTransformer())->exclude(['author']));
    }
}