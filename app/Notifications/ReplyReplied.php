<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReplyReplied extends Notification
{
    use Queueable;

    /**
     * @var Reply
     */
    public $reply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'reply_id' => $this->reply->id,
            'reply_body' => $this->reply->body,
            'reply_author_type' => $this->reply->user_id ? 'user':'organization',
            'reply_author_id' => $this->reply->user_id ? $this->reply->user_id : $this->reply->organization_id,
            'reply_author_name' => $this->reply->user_id ? $this->reply->author->nickname : $this->reply->author->username,
            'reply_author_avatar' => $this->reply->author->avatar,
            'parent_reply_id' => $this->reply->parentReply->id,
            'parent_reply_body' => $this->reply->parentReply->body,
        ];
    }
}
