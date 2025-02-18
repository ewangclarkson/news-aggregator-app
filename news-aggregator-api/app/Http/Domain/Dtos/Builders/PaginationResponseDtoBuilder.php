<?php

namespace App\Http\Domain\Dtos\Builders;

use App\Http\Domain\Dtos\PaginationResponseDto;

/**
 * Class PaginationResponseDtoBuilder
 *
 * A builder class for constructing instances of PaginationResponseDto.
 *
 * This class provides a fluent interface for setting the properties
 * of the PaginationResponseDto and creating an instance of it.
 *
 * @package App\Http\Domain\Dtos\Builders
 */
class PaginationResponseDtoBuilder
{
    private $data;
    private $pagination;

    /**
     * Set the paginated items.
     *
     * @param array $data The paginated items.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set the pagination metadata.
     *
     * @param int $currentPage The current page number.
     * @param int $totalPages The total number of pages.
     * @param int $totalItems The total number of items.
     * @param int $itemsPerPage The number of items per page.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setPagination($currentPage, $totalPages, $totalItems, $itemsPerPage)
    {
        $this->pagination = [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
        ];
        return $this;
    }

    /**
     * Build and return a PaginationResponseDto instance.
     *
     * @return PaginationResponseDto Returns a new instance of PaginationResponseDto
     *                              populated with the set properties.
     */
    public function build()
    {
        return new PaginationResponseDto($this->data, $this->pagination);
    }
}