<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\RelationNotFoundException;

/**
 * Class Category
 * @package App\Models
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property Category $parent
 * @property Collection $children
 */
class Category extends Model
{
    public function parent()
    {
        if ($this->parent_id === 0)
        {
            throw new RelationNotFoundException();
        }
        return $this->belongsTo('App\Models\Category','parent_id');
    }

    public function children()
    {
        if ($this->parent_id != 0)
        {
            throw new RelationNotFoundException();
        }
        return $this->hasMany('App\Models\Category','parent_id');
    }
}
