<?php

namespace App\Fractal;

use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Scope;

class CustomManager extends Manager
{
    /**
     * Factory used to create new configured scopes.
     *
     * @var ScopeFactoryInterface
     */
    private $scopeFactory;

    public function __construct(ScopeFactoryInterface $scopeFactory = null)
    {
        $this->scopeFactory = $scopeFactory ?: new CustomScopeFactory();
    }

    public function createData(ResourceInterface $resource, $scopeIdentifier = null, Scope $parentScopeInstance = null)
    {
        if ($parentScopeInstance !== null) {
            return $this->scopeFactory->createChildScopeFor($this, $parentScopeInstance, $resource, $scopeIdentifier);
        }

        return $this->scopeFactory->createScopeFor($this, $resource, $scopeIdentifier);
    }
}