<?php

namespace App\Transformers;

use App\Models\Article;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract
{
    /**
     * @param Article $article
     * @return array
     */
    public function transform(Article $article)
    {
        return [
            'title' => $article->title,
            'body' => $article->body,
        ];
    }
}