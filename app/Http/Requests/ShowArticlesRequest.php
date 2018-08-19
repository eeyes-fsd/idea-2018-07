<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ShowArticlesRequest extends Request
{
    public function rules()
    {
        return [
            'orderBy' => [
                Rule::in(['id','updated_at', 'created_at', 'reply_count', 'favorite_count', 'like_count','view_count']),
            ],
            'orderMode' => [
                Rule::in('desc','asc'),
            ],
            'author_type' => [
                Rule::in('user','organization'),
            ],
            'author_id' => 'integer',

            'category_id' => 'integer',
            'per_page' => 'integer',
        ];
    }
}
