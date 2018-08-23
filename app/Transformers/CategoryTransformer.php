<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;
use function Sodium\crypto_box_publickey_from_secretkey;

class CategoryTransformer extends TransformerAbstract
{
    
    protected $availableIncludes = ['parent','children'];

    public function __construct($parameters = null)
    {
        if ($parameters === null) {
            return ;
        }

        if (array_key_exists('includes',$parameters)) {
            $includes = array_intersect($this->availableIncludes,$parameters['includes']);
            $this->defaultIncludes = array_merge($this->defaultIncludes,$includes);
        }
        //这里提供一种include的思路，不完善。
    }

    public function includeParent(Category $category)
    {
        if ($category->hasParent()) {
            return $this->item($category->parent,new CategoryTransformer());
        }
        return null;
    }

    public function includeChildren(Category $category)
    {
        if ($category->hasChildren()) {
            return $this->collection($category->children,new CategoryTransformer());
        }
        return null;
    }

    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'parent_id' => $category->parent_id,
            'article_count' => $category->article_count,
        ];
    }
}

