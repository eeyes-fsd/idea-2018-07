<?php

namespace App\Models;

use Carbon\Carbon;
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
 * @property Article $articles
 */
class Organization extends Authenticatable implements JWTSubject
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'profile_photo', 'active',
        'phone', 'qq', 'email_visibility', 'qq_visibility',
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
}
