<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Notifications\Dispatcher;

trait Notifiable
{
    use HasDatabaseNotifications, RoutesNotifications {
        notify as protected laravelNotify;
    }
    use GetUserTrait;

    protected $availableTypes = ['article_replied','reply_replied','private_message',
        'article_liked','reply_liked'];

    public function notify($instance)
    {
        if ($this === $this->getUserOrActiveOrganization()) {
            return ;
        }

        $this->increment('notification_count',1);
        $this->laravelNotify($instance);
    }

    public function readsNotifications($elements)
    {
        if (is_array(json_decode($elements))) {
            $notifications = $this->unreadNotifications()->whereIn('id',$elements)->get();
        } elseif (is_string($elements)) {
            $notifications = $this->unreadNotifications()->where('id',$elements)->get();
        } else {
            throw new \Exception();
        }
        $notifications->markAsRead();
        $this->decrement('notification_count', $notifications->count());
        return $notifications->count();
    }

    public function getNotifications($params)
    {
        if (array_key_exists('state',$params) && $params['state'] == 'unread') {
            $query = $this->unreadNotifications();
        } else {
            $query = $this->notifications();
        }

        if (array_key_exists('type',$params) && in_array($params['type'],$this->availableTypes)) {
            $type = 'App\Notifications\\' . studly_case($params['type']);
            $query = $query->where('type',$type);
        }
        return $query;
    }

}