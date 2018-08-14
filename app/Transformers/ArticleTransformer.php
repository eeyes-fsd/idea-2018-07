<?php

namespace App\Transformers;

use App\Models\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['author'];

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

    public function includeAuthor(Article $article)
    {

        if ($article->user_id)
        {
            return $this->item($article->author, new UserTransformer());
        }
        elseif ($article->organization_id)
        {
            return $this->item($article->author, new OrganizationTransformer());
        }
        throw new ModelNotFoundException();
    }
}