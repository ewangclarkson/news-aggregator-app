<?php

namespace App\Http\Domain\Dtos;

use App\Http\Domain\Dtos\Builders\NewsSourceResponseDtoBuilder;

/**
 * Class NewsSourceResponseDto
 *
 * Data Transfer Object (DTO) for news source data.
 *
 * This class encapsulates the data structure for a news source,
 * including its unique identifier and name.
 *
 * @package App\Http\Domain\Dtos
 */
class NewsSourceResponseDto
{
    public $id;
    public $name;

    /**
     * NewsSourceResponseDto constructor.
     *
     * @param int $id The unique identifier for the news source.
     * @param string $name The name of the news source.
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Create a new instance of the NewsSourceResponseDtoBuilder.
     *
     * @return NewsSourceResponseDtoBuilder Returns a new instance of the builder
     *                                       for constructing NewsSourceResponseDto.
     */
    public static function builder(): NewsSourceResponseDtoBuilder
    {
        return new NewsSourceResponseDtoBuilder();
    }
}