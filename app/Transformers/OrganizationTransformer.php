<?php

namespace App\Transformers;

use App\Models\Organization;
use League\Fractal\TransformerAbstract;

class OrganizationTransformer extends TransformerAbstract
{
    public function transform(Organization $organization)
    {
        return [
            //TODO 完成 Organization Fractal 转换器
        ];
    }
}