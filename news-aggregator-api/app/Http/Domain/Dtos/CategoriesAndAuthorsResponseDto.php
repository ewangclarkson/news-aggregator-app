<?php

namespace App\Http\Domain\Dtos;

use App\Http\Domain\Dtos\Builders\CategoriesAndAuthorsResponseDtoBuilder;

/**
 * Class CategoriesAndAuthorsResponseDto
 *
 * Data Transfer Object (DTO) for unique categories and authors data.
 *
 * This class encapsulates the data structure for unique categories and authors,
 * providing a clear format for the response.
 *
 * @package App\Http\Domain\Dtos
 */
class CategoriesAndAuthorsResponseDto
{
    public $categories;
    public $authors;
    public $preferences;

    /**
     * CategoriesAndAuthorsResponseDto constructor.
     *
     * @param array $categories The unique categories.
     * @param array $authors The unique authors.
     */
    public function __construct(array $categories, array $authors, $preference)
    {
        $this->categories = $categories;
        $this->authors = $authors;
        $this->preferences = $preference;
    }

    /**
     * Create a new instance of the CategoriesAndAuthorsResponseDtoBuilder.
     *
     * @return CategoriesAndAuthorsResponseDtoBuilder Returns a new instance of the builder
     *                                                 for constructing CategoriesAndAuthorsResponseDto.
     */
    public static function builder(): CategoriesAndAuthorsResponseDtoBuilder
    {
        return new CategoriesAndAuthorsResponseDtoBuilder();
    }
}