<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['parent','children'];

    public function includeParent(Category $category)
    {
        if ($category->parent()) {
            return $this->item($category->parent(),new CategoryTransformer());
        }
        return [];
    }

    public function includeChildren(Category $category)
    {
        if ($category->children()) {
            return $this->collection($category->children(),new CategoryTransformer());
        }
        return [];
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

