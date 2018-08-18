<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Support\Collection;

/**
 * Class Article
 * @package App\Models
 *
 * @property int $id 文章ID
 * @property int $user_id 作者（个人用户）ID
 * @property int $organization_id 作者（社团用户）ID
 * @property int $category_id 所属分类 ID
 * @property string $title 文章标题
 * @property string $body 文章内容
 * @property bool $anonymous 是否匿名
 * @property int $view_count 查看计数
 * @property int $like_count 点赞计数
 * @property int $favorite_count 收藏计数
 * @property int $reply_count 回复计数
 * @property Carbon $created_at 创建于
 * @property Carbon $updated_at 更改于
 * @property User|Organization $author 作者
 * @property Category $category 分类
 * @property Collection $replies 分类
 */
class Article extends Model
{

    protected $fillable = [
        'user_id', 'organization_id', 'category_id',
        'title', 'body', 'anonymous',
    ];

    public function author()
    {
        if ($this->user_id) {
            return $this->belongsTo('App\Models\User','user_id');
        } elseif ($this->organization_id) {
            return $this->belongsTo('App\Models\Organization','organization_id');
        }

        throw new ModelNotFoundException("No related author found");
    }

    public function category()
    {
        if (Category::find($this->category_id)->parent_id === 0)
        {
            throw new RelationNotFoundException();
        }
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class,'article_id');
    }

    public function scopeOfCategory($query, $category_id)
    {
        if ($category_id === 0) {
            return $query;
        }
        $category = Category::findOrFail($category_id);
        if ($category->parent_id === 0) {
            $categories = Category::where('parent_id', $category->id)->get()->toArray();
            $category_ids = array_map(function ($category) {
                return $category['id'];
            }, $categories);
            return $query->whereIn('category_id', $category_ids);
        } else {
            return $query->where('category_id',$category_id);
        }
    }

    public function scopeOfAuthor($query, $author_type, $author_id)
    {
        if (!in_array($author_type,['user','organization'])) {
            return $query;
        } else {
            return $query->where($author_type . '_id', $author_id)->where('anonymous',false);
        }
    }
}
