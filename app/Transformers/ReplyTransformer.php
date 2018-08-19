<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-17
 * Time: 16:26
 */

namespace App\Transformers;


use App\Models\Reply;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['author'];

    public function transform(Reply $reply)
    {
        return [
            'id' => $reply->id,
            'article_id' => $reply->article_id,
            'reply_id' => $reply->reply_id,
            'body' => $reply->body,
            'like_count' => $reply->like_count,
            'author_type' => $reply->user_id ? 'user' : 'organization',
            'created_at' => $reply->created_at->diffForHumans(),
            'updated_at' => $reply->created_at->diffForHumans(),
        ];
    }

    public function includeAuthor(Reply $reply)
    {
        if ($reply->user_id) {
            return $this->item($reply->author, new UserTransformer());
        } elseif ($reply->organization_id) {
            return $this->item($reply->author, new OrganizationTransformer());
        }
        throw new ModelNotFoundException();
    }
}