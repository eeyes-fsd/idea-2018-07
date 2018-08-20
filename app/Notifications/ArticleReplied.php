<?php

namespace App\Notifications;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ArticleReplied extends Notification
{
    use Queueable;

    /**
     * @var Reply
     */
    public $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $article = $this->reply->article;

        return [
            'reply_id' => $this->reply->id,
            'reply_body' => $this->reply->body,
            'reply_author_type' => $this->reply->user_id ? 'user':'organization',
            'reply_author_id' => $this->reply->user_id ? $this->reply->user_id : $this->reply->organization_id,
            'reply_author_name' => $this->reply->user_id ? $this->reply->author->nickname : $this->reply->author->username,
            'reply_author_avatar' => $this->reply->author->avatar,
            'article_id' => $article->id,
            'article_title' => $article->title,
        ];
    }
}
