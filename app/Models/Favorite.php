<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class Favorite
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $organization_id
 * @property int $article_id
 * @property User|Organization $author
 * @property Article $article
 */
class Favorite extends Model
{
    protected $fillable = ['user_id','organization_id','article_id'];

    public function author()
    {
        if ($this->user_id) {
            return $this->belongsTo('App\Models\User','user_id');
        } elseif ($this->organization_id) {
            return $this->belongsTo('App\Models\Organization','organization_id');
        }

        throw new ModelNotFoundException("No related author found");
    }

    public function scopeOfAuthor($query, $author_type, $author_id)
    {
        if (!in_array($author_type,['user','organization'])) {
            return $query;
        } else {
            return $query->where($author_type . '_id', $author_id);
        }
    }

    public function scopeOfAuthorByObject($query, $author)
    {
        if ($author instanceof User) {
            return $query->where('user_id',$author->id);
        } elseif ($author instanceof Organization) {
            return $query->where('organization_id', $author->id);
        } else {
            throw new ModelNotFoundException();
        }
    }

    public function article()
    {
        if (Article::findOrFail($this->article_id)) {
            return $this->belongsTo(Article::class,'article_id');
        } else {
            throw new RelationNotFoundException();
        }
    }

}
