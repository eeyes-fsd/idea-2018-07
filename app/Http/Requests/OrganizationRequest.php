<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrganizationRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->isMethod('put')) {
            $organization = Organization::find($this->route('organization'));
            if(!$organization){
                return false;
            }else{
                if(Auth::guard('api_organization')->user()->cant('update',$organization))
                {
                    return false;
                }else{
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
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
