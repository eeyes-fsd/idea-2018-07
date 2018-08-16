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
    protected $defaultIncludes = ['author','category'];

    /**
     * @param Article $article
     * @return array
     */
    public function transform(Article $article)
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'body' => $article->body,
            'anonymous' => $article->anonymous,
            'view_count' => $article->view_count,
            'like_count' => $article->like_count,
            'favorite_count' => $article->favorite_count,
            'reply_count' => $article->reply_count,
            'created_at' => $article->created_at->diffForHumans(),
            'updated_at' => $article->updated_at->diffForHumans(),
        ];
    }

    public function includeAuthor(Article $article)
    {
        if ($article->anonymous) {
            return null;
        } elseif ($article->user_id) {
            return $this->item($article->author, new UserTransformer());
        } elseif ($article->organization_id) {
            return $this->item($article->author, new OrganizationTransformer());
        }
        throw new ModelNotFoundException();
    }

    public function includeCategory(Article $article)
    {
        return $this->item($article->category,new CategoryTransformer());
    }
}