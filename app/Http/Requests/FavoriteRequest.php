<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FavoriteRequest extends Request
{
    public function rules()
    {
        return [
            'author_type' => [
                'required',
                Rule::in(['user','organization','me'])
            ],
            'author_id' => 'required|integer' //if author_type is me, author_id is required but of no use.
        ];
    }
}
