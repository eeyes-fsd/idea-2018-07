<?php

namespace App\Models;

use App\Traits\SearchTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Organization
 * @package App\Models
 *
 * @property int $id 用户 ID
 * @property string $username 社团名
 * @property string $email 社团 email
 * @property string $avatar 用户头像地址
 * @property string $signature 用户个性签名
 * @property bool $active 用户是否审核通过
 * @property string $qq 用户 QQ
 * @property bool $qq_visibility 用户 QQ 是否可见
 * @property bool $email_visibility 用户 email 是否可见
 * @property Carbon $created_at 创建于
 * @property Carbon $updated_at 更改于
 * @property Collection $articles
 * @property Collection $favoriteArticles
 * @property Collection $likedReplies
 */
class Organization extends Authenticatable implements JWTSubject
{
    use HasRoles;
    use Notifiable;
    use SearchTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'avatar', 'active',
        'phone', 'qq', 'email_visibility', 'qq_visibility', 'signature'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Rest omitted for brevity
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Article','organization_id');
    }

    public function favoriteArticles()
    {
        return $this->hasManyThrough(Article::class,
            Favorite::class,
            'organization_id',
            'id',
            'id',
            'article_id'
        );
    }

    public function likedArticles()
    {
        return $this->hasManyThrough(Article::class,
            Like::class,
            'user_id',
            'id',
            'id',
            'article_id'
        );
    }

    public function likedReplies()
    {
        return $this->hasManyThrough(Article::class,
            Like::class,
            'user_id',
            'id',
            'id',
            'reply_id'
        );
    }
}
