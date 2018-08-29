<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-18
 * Time: 0:25
 */

namespace App\Fractal;


use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\ScopeFactory;

class CustomScopeFactory extends ScopeFactory
{
    /**
     * @param Manager $manager
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @return Scope
     */
    public function createScopeFor(Manager $manager, ResourceInterface $resource, $scopeIdentifier = null)
    {
        return new CustomScope($manager, $resource, $scopeIdentifier);
    }
}