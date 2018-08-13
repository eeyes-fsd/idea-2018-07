<?php

namespace App\Transformers;

use App\Models\Article;

class ArticleTransformer
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