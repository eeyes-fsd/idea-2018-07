<?php

namespace App\Notifications;

use App\Models\Like;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ArticleLiked extends Notification
{
    use Queueable;

    /**
     * @var Like
     */
    public $like ;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'like_id' => $this->like->id,
            'like_author_type' => $this->like->user_id ? 'user':'organization',
            'like_author_id' => $this->like->user_id ? $this->like->user_id : $this->like->organization_id,
            'like_author_name' => $this->like->user_id ? $this->like->author->nickname : $this->like->author->username,
            'like_author_avatar' => $this->like->author->avatar,
            'article_id' => $this->like->article->id,
            'article_title' => $this->like->article->title,
        ];
    }
}
