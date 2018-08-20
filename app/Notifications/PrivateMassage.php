<?php

namespace App\Notifications;

use App\Models\Organization;
use App\Models\User;
use App\Traits\GetUserTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PrivateMassage extends Notification
{
    use Queueable;

    use GetUserTrait;

    /**
     * @var User|Organization
     */
    protected $user;

    /**
     * @var string
     */
    protected $body;
    /**
     * PrivateMassage constructor.
     * @param $body string
     */
    public function __construct($body)
    {
        $this->body = $body;
        $this->user = $this->getUserOrActiveOrganization();
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

    public function toArray($notifiable)
    {
        return [
            'author_type' => $this->user instanceof User ? 'user' : 'organization',
            'author_id' => $this->user->id,
            'author_name' => $this->user instanceof User ? $this->user->nickname : $this->user->username,
            'author_avatar' => $this->user->avatar,
            'body' => $this->body,
        ];
    }
}
