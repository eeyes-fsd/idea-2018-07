<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Category
 * @package App\Models
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property int $article_count
 * @property Category $parentOrFail
 * @property Category|null $parent
 * @property Collection $childrenOrFail
 * @property Collection|null $children
 */
class Category extends Model
{
    use HasRoles;

    public function parent()
    {
        if ($this->parent_id === 0) {
            return null;
        }
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function children()
    {
        if ($this->parent_id != 0) {
            return null;
        }
        return $this->hasMany(Category::class,'parent_id');
    }

    public function parentOrFail()
    {
        if ($this->parent_id === 0)
        {
            throw new RelationNotFoundException();
        }
        return $this->belongsTo('App\Models\Category','parent_id');
    }

    public function childrenOrFail()
    {
        if ($this->parent_id != 0)
        {
            throw new RelationNotFoundException();
        }
        return $this->hasMany('App\Models\Category','parent_id');
    }
}
