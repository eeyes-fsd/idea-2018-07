<?php

namespace App\Models;

use App\Traits\SearchTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $nickname
 * @property string $avatar
 * @property string $signature
 * @property string $phone
 * @property string $qq
 * @property int $notification_count
 * @property bool $phone_visibility
 * @property bool $email_visibility
 * @property bool $qq_visibility
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection $articles
 * @property Collection $favoriteArticles
 * @property Collection $likedArticles
 * @property Collection $likedReplies
 */
class User extends Authenticatable implements JWTSubject
{
    use HasRoles;
    use Notifiable;
    use SearchTrait;

    protected $guard_name="web";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','name', 'email', 'password', 'avatar', 'nickname',
        'signature', 'phone', 'qq', 'phone_visibility', 'email_visibility', 'qq_visibility'
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
        return $this->hasMany('App\Models\Article','user_id');
    }

    public function favoriteArticles()
    {
        return $this->hasManyThrough(Article::class,
            Favorite::class,
            'user_id',
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
