<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Auth;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = ['articles'];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    protected $includeArticleCount;

    public function __construct($includeArticleCount = 3)
    {
        $this->includeArticleCount = $includeArticleCount;
    }

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'NetID' => $user->username,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'signature' => $user->signature,
            'phone' => $user->phone_visibility ? $user->phone : '***',
            'email' => $user->email_visibility ? $user->email : '***',
            'qq' => $user->qq_visibility ? $user->qq : '***',
            //todo
            'article_count' => $user->articles->count(),
            'phone_visibility' => $user->phone_visibility,
            'email_visibility' => $user->email_visibility,
            'qq_visibility' => $user->qq_visibility,
            'created_at' => $user->created_at->toDateTimeString(),
        ];

        if (Auth::guard('api_user')->check()
            && Auth::guard('api_user')->user()->username === $user->username) {
            $data['phone'] = $user->phone;
            $data['email'] = $user->email;
            $data['qq'] = $user->qq;
            $data['notification_count'] = $user->notification_count;
        }

        return $data;
    }

    public function includeArticles(User $user)
    {
        return $this->collection($user->articles()->take($this->includeArticleCount)->get(),(new ArticleTransformer())->exclude(['author']));
    }
}