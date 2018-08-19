<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-18
 * Time: 23:17
 */

namespace App\Traits;


trait SearchTrait
{
    public function sql_filter($sql)
    {
        return str_replace(
            ["'", '"', '&', '|', '@', '%', '*', '(', ')', '-', '\\'],
            ['', '', '', '', '', '', '', '', '', '', ''],
            $sql);
    }

    public function scopeSearch($query, $wd, $search_fields)
    {
        $wd = $this->sql_filter($wd);
        if (empty($wd)) {
            return $query->whereRaw('FALSE');
        }

        // replace '你好世界' with '%你%好%世%界%';
        $wd = '%' . preg_replace('/./u', '$0%', $wd);

        foreach ($search_fields as $field) {
            $query->orWhere($field, 'like', $wd);
        }

        return $query;
    }

    public function scopeOrderByDate($query, $is_desc = true)
    {
        if($is_desc){
            return $query->orderBy('updated_at', 'desc');
        }else{
            return $query->orderBy('updated_at');
        }
    }
}