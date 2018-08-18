<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class Reply
 * @package App
 *
 * @property  int $id 回复id
 * @property  int $article_id 所属文章id
 * @property  int $reply_id  父回复id，可空
 * @property  int $user_id 作者id，可空
 * @property  int $organization_id 社团id，可空
 * @property  int $like_count
 * @property  string $body 正文
 * @property  Carbon $created_at 创建于
 * @property  Carbon $updated_at 更改于
 * @property  User|Organization $author 作者
 * @property  Article $article 作者
 */
class Reply extends Model
{
    protected $fillable = [
        'article_id','reply_id','user_id','organization_id','body'
    ];

    public function author()
    {
        if ($this->user_id) {
            return $this->belongsTo(User::class,'user_id');
        } elseif ($this->organization_id) {
            return $this->belongsTo(Organization::class,'organization');
        }
        throw new ModelNotFoundException('No related author found');
    }

    public function article()
    {
        return $this->belongsTo(Article::class,'article_id');
    }

}
