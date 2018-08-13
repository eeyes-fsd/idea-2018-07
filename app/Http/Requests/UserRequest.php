<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->isMethod('put')) {
            $user = User::find($this->route('user'));
            if (!$user) {
                return false;
            } else {
                if (Auth::guard('api_user')->user()->cant('update', $user)) {
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return true;
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
