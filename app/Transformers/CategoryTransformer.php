<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    
    protected $availableIncludes = ['parent','children'];

    public function __construct($parameters = null)
    {
        //这里提供一种include的思路，不完善。
//        if (is_array($parameters) && key_exists('include',$parameters)) {
//            $this->defaultIncludes = $parameters['include'];
//        }
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

