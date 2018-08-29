<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class StorePrivateMessageRequest extends Request
{

    public function rules()
    {
        return [
            'body' => 'required',
            'user_id' => 'required',
            'user_type' => [
                'required',
                Rule::in(['user','organization','all']),
            ],
        ];
    }
}
