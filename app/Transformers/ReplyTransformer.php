<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-17
 * Time: 16:26
 */

namespace App\Transformers;


use App\Models\Like;
use App\Models\Reply;
use App\Models\User;
use App\Traits\GetUserTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract
{
    use GetUserTrait;

    protected $defaultIncludes = ['author'];

    protected $availableIncludes = ['children'];

    protected $infoForCur = false;

    public function __construct($params=[])
    {
        if (array_key_exists('info_for_cur',$params)) {
            $this->infoForCur = $params['info_for_cur'];
        }
    }

    public function transform(Reply $reply)
    {
        $data = [
            'id' => $reply->id,
            'article_id' => $reply->article_id,
            'reply_id' => $reply->reply_id,
            'body' => $reply->body,
            'like_count' => $reply->like_count,
            'author_type' => $reply->user_id ? 'user' : 'organization',
            'created_at' => $reply->created_at->toDateTimeString(),
            'updated_at' => $reply->created_at->toDateTimeString(),
        ];

        if ($this->infoForCur) {
            $user = $this->getUserOrActiveOrganization();
            $user_type = $user instanceof User? 'user' : 'organization';
            if ($user) {
                $like_query = Like::where("{$user_type}_id",$user->id)
                    ->where('reply_id',$reply->id);
                $data['liked'] = $like_query->count();
            }
        }

        return $data;
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

    public function includeChildren(Reply $reply)
    {
        if (!$reply->hasParentReply()) {
            return $this->collection($reply->children, new ReplyTransformer());
        } else {
            throw new ModelNotFoundException();
        }
    }
}
