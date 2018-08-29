<?php

namespace App\Policies;

use App\Models\Article;

class ArticlePolicy extends Policy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update($user, Article $article)
    {
        return $this->isUserEqual($user,$article->author);
    }

    public function delete($user, Article $article)
    {
        return $this->isUserEqual($article->author,$user);
    }
}
