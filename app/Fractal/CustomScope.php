<?php
/**
 * Created by PhpStorm.
 * User: Cantjie
 * Date: 2018-8-18
 * Time: 0:27
 */

namespace App\Fractal;


use League\Fractal\Resource\Collection;
use League\Fractal\Scope;

class CustomScope extends Scope
{
    protected $key;

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getKey($key)
    {
        return $key;
    }

    /**
     * Convert the current data for this scope to an array.
     *
     * @return array
     */
    /**
     * Convert the current data for this scope to an array.
     *
     * @return array
     */
    public function toArray()
    {
        list($rawData, $rawIncludedData) = $this->executeResourceTransformers();

        $serializer = $this->manager->getSerializer();
        $rawData = $rawData;
        $data = $this->serializeResource($serializer, $rawData);

        // If the serializer wants the includes to be side-loaded then we'll
        // serialize the included data and merge it with the data.
        if ($serializer->sideloadIncludes()) {
            //Filter out any relation that wasn't requested
            $rawIncludedData = array_map(array($this, 'filterFieldsets'), $rawIncludedData);

            $includedData = $serializer->includedData($this->resource, $rawIncludedData);

            // If the serializer wants to inject additional information
            // about the included resources, it can do so now.
            $data = $serializer->injectData($data, $rawIncludedData);

            if ($this->isRootScope()) {
                // If the serializer wants to have a final word about all
                // the objects that are sideloaded, it can do so now.
                $includedData = $serializer->filterIncludes(
                    $includedData,
                    $data
                );
            }

            $data = array_merge($data, $includedData);
        }

        if ($this->resource instanceof Collection) {
            if ($this->resource->hasCursor()) {
                $pagination = $serializer->cursor($this->resource->getCursor());
            } elseif ($this->resource->hasPaginator()) {
                $pagination = $serializer->paginator($this->resource->getPaginator());
            }

            if (! empty($pagination)) {
                $this->resource->setMetaValue(key($pagination), current($pagination));
            }
        }

        // Pull out all of OUR metadata and any custom meta data to merge with the main level data
        $meta = $serializer->meta($this->resource->getMeta());

        // in case of returning NullResource we should return null and not to go with array_merge
        if (is_null($data)) {
            if (!empty($meta)) {
                return $meta;
            }
            return null;
        }
        if ($this->key) {
            return array_merge([$this->key=>$data], $meta);
        } else {
            return array_merge($data, $meta);
        }
    }
}