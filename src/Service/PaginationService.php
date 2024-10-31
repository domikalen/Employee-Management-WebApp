<?php

namespace App\Service;

class PaginationService
{
    private int $itemsPerPage;

    public function __construct(int $itemsPerPage = 10)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getPagination(int $totalItems, int $currentPage): array
    {
        $totalPages = (int) ceil($totalItems / $this->itemsPerPage);

        return [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'hasPreviousPage' => $currentPage > 1,
            'hasNextPage' => $currentPage < $totalPages,
            'previousPage' => $currentPage > 1 ? $currentPage - 1 : null,
            'nextPage' => $currentPage < $totalPages ? $currentPage + 1 : null,
        ];
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
