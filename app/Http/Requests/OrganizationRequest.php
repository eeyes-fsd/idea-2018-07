<?php

namespace App\Http\Requests;

class OrganizationRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //创建的时候需要，update的时候不需要rules
        if ($this->isMethod('post')) {
            return [
                'username' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ];
        } else {
            return [];
        }
    }
}
