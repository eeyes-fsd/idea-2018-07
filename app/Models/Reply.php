<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
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
 * @property  Article $article 文章
 * @property  Reply $parentReply 父回复
 * @property  Collection $children 子回复
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
            return $this->belongsTo(Organization::class,'organization_id');
        }
        throw new ModelNotFoundException('No related author found');
    }

    public function article()
    {
        return $this->belongsTo(Article::class,'article_id');
    }

    public function hasParentReply()
    {
        return $this->reply_id != 0 ;
    }

    public function parentReply()
    {
        return $this->belongsTo(Reply::class,'reply_id');
    }

    public function scopeOfFirstLevel($query)
    {
        return $query->where('reply_id',0);
    }

    public function scopeOfArticle($query, $article)
    {
        if ($article instanceof Article) {
            return $query->where('article_id',$article->id);
        } else {
            return $query->where('article_id',$article);
        }
    }
    public function children()
    {
        return $this->hasMany(Reply::class,'reply_id');
    }
}
