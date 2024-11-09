<?php

namespace App\Service;

class PaginationService
{
    private int $itemsPerPage;

    public function __construct(int $itemsPerPage = 5)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getPagination(int $totalItems, int $currentPage, ?string $query = null): array
    {
        $totalPages = (int) ceil($totalItems / $this->itemsPerPage);

        return [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'hasPreviousPage' => $currentPage > 1,
            'hasNextPage' => $currentPage < $totalPages,
            'previousPage' => $currentPage > 1 ? $currentPage - 1 : null,
            'nextPage' => $currentPage < $totalPages ? $currentPage + 1 : null,
            'query' => $query,
        ];
    }


    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
