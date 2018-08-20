<?php

namespace App\Notifications;

use App\Models\Like;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReplyLiked extends Notification
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
            'reply_id' => $this->like->reply->id,
            'reply_body' => $this->like->reply->body,
        ];
    }
}
