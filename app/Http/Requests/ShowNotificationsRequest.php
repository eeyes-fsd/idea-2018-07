<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class ShowNotificationsRequest extends Request
{
    public function rules()
    {
        return [
            'type' => Rule::in(['article_replied','reply_replied','private_message',
                'article_liked','reply_liked','all'])
        ];
    }
}
