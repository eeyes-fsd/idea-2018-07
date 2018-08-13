<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Auth\Access\AuthorizationException;

class BaseFormRequest extends FormRequest
{
    protected function failedAuthorization()
    {
        throw new AuthorizationException('认证失败，权限不足。');
    }
}
