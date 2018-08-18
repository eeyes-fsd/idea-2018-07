<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyRequest extends Request
{

    public function rules()
    {
        return [
            'body' => 'required',
            'article_id' => 'required',
        ];
    }
}
