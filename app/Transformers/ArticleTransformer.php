<?php

namespace App\Transformers;

use App\Models\Article;
use App\Models\Favorite;
use App\Models\Like;
use App\Models\User;
use App\Traits\GetUserTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract
{
    use GetUserTrait;
    /**
     * @var array
     */
    protected $defaultIncludes = ['author','category'];

    /**
     * @var array
     */
    protected $availableIncludes = ['replies',];

    protected $infoForCur = false;

    public function __construct($params=[])
    {
        if (array_key_exists('info_for_cur',$params)) {
            $this->infoForCur = $params['info_for_cur'];
        }
    }
    
    /**
     * @param Article $article
     * @return array
     */
    public function transform(Article $article)
    {
        $data = [
            'id' => $article->id,
            'title' => $article->title,
            'body' => $article->body,
            'anonymous' => $article->anonymous,
            'cover' => $article->cover,
            'author_type' => $article->user_id ? 'user' : 'organization',
            'view_count' => $article->view_count,
            'like_count' => $article->like_count,
            'favorite_count' => $article->favorite_count,
            'reply_count' => $article->reply_count,
            'created_at' => $article->created_at->toDateTimeString(),
            'updated_at' => $article->updated_at->toDateTimeString(),
        ];

        if ($this->infoForCur) {
            $user = $this->getUserOrActiveOrganization();
            $user_type = $user instanceof User? 'user' : 'organization';
            if ($user) {
                $like_query = Like::where("{$user_type}_id",$user->id)
                    ->where('article_id',$article->id);
                $favorite_query = Favorite::where("{$user_type}_id",$user->id)
                    ->where('article_id',$article->id);
                $data['liked'] = $like_query->count();
                $data['favorited'] = $favorite_query->count();
            }
        }

        return $data;
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
        return $this->item($article->category,new CategoryTransformer(['includes' => ['parent']]));
    }

    public function includeReplies(Article $article)
    {
        return $this->collection($article->replies, new ReplyTransformer());
    }

    public function exclude(array $params)
    {
        $this->defaultIncludes = array_diff($this->defaultIncludes, $params);;
        return $this;
    }

}