<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Article extends Model
{
    public function author()
    {
        if ($this->user_id)
        {
            return $this->belongsTo('App\Models\User','user_id');
        }
        elseif ($this->organization_id)
        {
            return $this->belongsTo('App\Models\Organization','organization_id');
        }

        throw new ModelNotFoundException("No related author found");
    }
}
