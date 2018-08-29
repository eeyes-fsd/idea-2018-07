<?php

namespace App\Transformers;

use App\Notifications\ArticleLiked;
use App\Notifications\ArticleReplied;
use App\Notifications\PrivateMessage;
use App\Notifications\ReplyLiked;
use App\Notifications\ReplyReplied;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    
    protected $availableIncludes = [];

    public function transform($notification)
    {
        if ($notification->type === ArticleReplied::class) {
            $data = [
                'reply_id' => $notification->data['reply_id'],
                'reply_body' => $notification->data['reply_body'],
                'reply_author_type' => $notification->data['reply_author_type'],
                'reply_author_id' => $notification->data['reply_author_id'],
                'reply_author_name' => $notification->data['reply_author_name'],
                'reply_author_avatar' => $notification->data['reply_author_avatar'],
                'article_id' => $notification->data['article_id'],
                'article_title' => $notification->data['article_title'],
            ];
        } elseif ($notification->type === ReplyReplied::class) {
            $data = [
                'reply_id' => $notification->data['reply_id'],
                'reply_body' => $notification->data['reply_body'],
                'reply_author_type' => $notification->data['reply_author_type'],
                'reply_author_id' => $notification->data['reply_author_id'],
                'reply_author_name' => $notification->data['reply_author_name'],
                'reply_author_avatar' => $notification->data['reply_author_avatar'],
                'parent_reply_id' => $notification->data['parent_reply_id'],
                'parent_reply_body' => $notification->data['parent_reply_body'],
            ];
        } elseif ($notification->type === PrivateMessage::class) {
            $data = [
                'author_type' => $notification->data['author_type'],
                'author_id' => $notification->data['author_id'],
                'author_name' => $notification->data['author_name'],
                'author_avatar' => $notification->data['author_avatar'],
                'body' => $notification->data['body'],
            ];
        } elseif ($notification->type === ReplyLiked::class){
            $data = [
                'like_author_type' => $notification->data['like_author_type'],
                'like_author_id' => $notification->data['like_author_id'],
                'like_author_name' => $notification->data['like_author_name'],
                'like_author_avatar' => $notification->data['like_author_avatar'],
                'reply_id' => $notification->data['reply_id'],
                'reply_body' => $notification->data['reply_body'],
            ];
        } elseif ($notification->type === ArticleLiked::class){
            $data = [
                'like_author_type' => $notification->data['like_author_type'],
                'like_author_id' => $notification->data['like_author_id'],
                'like_author_name' => $notification->data['like_author_name'],
                'like_author_avatar' => $notification->data['like_author_avatar'],
                'article_id' => $notification->data['article_id'],
                'article_title' => $notification->data['article_title'],
            ];
        } else {
            throw new \Exception('通知类型错误');
        }
        $data = array_merge([
            'id' => $notification->id,
            'type' => snake_case(class_basename($notification->type)),
            'read_at' => !$notification->read_at ? null :$notification->read_at->toDateTimeString(),
            'created_at' => $notification->created_at->toDateTimeString(),
        ],$data);
        return $data;
    }
}

