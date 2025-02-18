<?php

namespace App\Http\Domain\Dtos;

use App\Http\Domain\Dtos\Builders\PaginationResponseDtoBuilder;

/**
 * Class PaginationResponseDto
 *
 * Data Transfer Object (DTO) for pagination response data.
 *
 * This class encapsulates the response data returned from pagination,
 * including the paginated items and pagination metadata.
 *
 * @package App\Http\Domain\Dtos
 */
class PaginationResponseDto
{
    public $data;
    public $meta;

    /**
     * PaginationResponseDto constructor.
     *
     * @param array $data The paginated items.
     * @param Pagination $meta The pagination metadata.
     */
    public function __construct($data, $meta)
    {
        $this->data = $data;
        $this->meta = $meta;
    }

    /**
     * Create a new instance of the PaginationResponseDtoBuilder.
     *
     * @return PaginationResponseDtoBuilder Returns a new instance of the builder
     *                                       for constructing PaginationResponseDto.
     */
    public static function builder()
    {
        return new PaginationResponseDtoBuilder();
    }
}