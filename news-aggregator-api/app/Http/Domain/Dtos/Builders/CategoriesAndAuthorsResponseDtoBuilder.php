<?php

namespace App\Http\Domain\Dtos\Builders;

use App\Http\Domain\Dtos\CategoriesAndAuthorsResponseDto;

/**
 * Class CategoriesAndAuthorsResponseDtoBuilder
 *
 * A builder class for constructing instances of CategoriesAndAuthorsResponseDto.
 *
 * This class provides a fluent interface for setting the properties
 * of the CategoriesAndAuthorsResponseDto and creating an instance of it.
 *
 * @package App\Http\Domain\Dtos\Builders
 */
class CategoriesAndAuthorsResponseDtoBuilder
{
    private $categories;
    private $authors;
    private $preferences;

    /**
     * Set the unique categories.
     *
     * @param array $categories The unique categories.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Set the unique authors.
     *
     * @param array $authors The unique authors.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setAuthors(array $authors): self
    {
        $this->authors = $authors;
        return $this;
    }

    /**
     * Set the current auth users preference
     *
     * @param array $preferences
     * @return $this
     */
    public function setUserPreference($preferences): self
    {
        $this->preference = $preferences;
        return $this;
    }
    /**
     * Build and return a CategoriesAndAuthorsResponseDto instance.
     *
     * @return CategoriesAndAuthorsResponseDto Returns a new instance of CategoriesAndAuthorsResponseDto
     *                                          populated with the set properties.
     */
    public function build(): CategoriesAndAuthorsResponseDto
    {
        return new CategoriesAndAuthorsResponseDto($this->categories, $this->authors, $this->preference);
    }
}