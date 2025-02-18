<?php

namespace App\Http\Domain\Dtos\Builders;

use App\Http\Domain\Dtos\NewsSourceResponseDto;

/**
 * Class NewsSourceResponseDtoBuilder
 *
 * A builder class for constructing instances of NewsSourceResponseDto.
 *
 * This class provides a fluent interface for setting the properties
 * of the NewsSourceResponseDto and creating an instance of it.
 *
 * @package App\Http\Domain\Dtos\Builders
 */
class NewsSourceResponseDtoBuilder
{
    private $id;
    private $name;

    /**
     * Set the ID of the news source.
     *
     * @param mixed $id The unique identifier for the news source.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set the name of the news source.
     *
     * @param string $name The name of the news source.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Build and return a NewsSourceResponseDto instance.
     *
     * @return NewsSourceResponseDto Returns a new instance of NewsSourceResponseDto
     *                               populated with the set properties.
     */
    public function build(): NewsSourceResponseDto
    {
        return new NewsSourceResponseDto($this->id, $this->name);
    }
}