<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\PaginatorInterface;

class CustomSerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return $data;
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return $data;
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null()
    {
        return [];
    }

    /**
     * Serialize the paginator.
     *
     * @param PaginatorInterface $paginator
     *
     * @return array
     */
    public function paginator(PaginatorInterface $paginator)
    {
        $currentPage = (int) $paginator->getCurrentPage();
        $lastPage = (int) $paginator->getLastPage();

        $pagination = [
            'total' => (int) $paginator->getTotal(),
            'count' => (int) $paginator->getCount(),
            'per_page' => (int) $paginator->getPerPage(),
            'current_page' => $currentPage,
            'total_pages' => $lastPage,
            'first_page_url' => $paginator->getUrl(1),
            'last_page_url' => $paginator->getUrl($lastPage),
        ];

//        $pagination['links'] = [];

        if ($currentPage > 1) {
            $pagination['previous_page_url'] = $paginator->getUrl($currentPage - 1);
        }

        if ($currentPage < $lastPage) {
            $pagination['next_page_url'] = $paginator->getUrl($currentPage + 1);
        }

        return [$pagination];
    }
}