<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ArticleRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('put')) {
            $article = Article::find($this->route('article'));
            if (!$article) {
                return false;
            } else {
                if($article->organization_id && Auth::guard('api_organization')->check()) {
                    return Auth::guard('api_organization')->can('updateArticles',$article);
                } elseif ($article->user_id && Auth::guard('api_user')->check()) {
                    return Auth::guard('api_user')->can('updateArticles',$article);
                } else {
                    return false;
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
            'title' => 'required',
            'body' => 'required',
        ];
    }
}
