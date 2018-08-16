<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['parent','children'];

    public function includeParent(Category $category)
    {
        return $this->item($category->parent(),new CategoryTransformer());
    }

    public function includeChildren(Category $category)
    {
        return $this->collection($category->children()->get(),new CategoryTransformer());
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

