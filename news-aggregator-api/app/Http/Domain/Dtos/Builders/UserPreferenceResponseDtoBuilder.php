<?php

namespace App\Http\Domain\Dtos\Builders;

use App\Http\Domain\Dtos\UserPreferenceResponseDto;

/**
 * Class UserPreferenceResponseDtoBuilder
 *
 * A builder class for constructing instances of UserResponseDto.
 *
 * This class provides a fluent interface for setting the properties
 * of the UserResponseDto and creating an instance of it.
 *
 * @package App\Http\Domain\Dtos\Builders
 */
class UserPreferenceResponseDtoBuilder
{
    private $id;
    private $source;
    private $category;
    private $authors;

    /**
     * Set the ID of the user.
     *
     * @param  $id - The unique identifier for the user.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set the source of the user.
     *
     * @param $source - The name of the user.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setSource($source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Set the category of the user.
     *
     * @param $category - The email of the user.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setCategory($category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Set the source of the user.
     *
     * @param $authors
     * @return $this Returns the builder instance for method chaining.
     */
    public function setAuthors($authors): self
    {
        $this->authors = $authors;
        return $this;
    }

    /**
     * Build and return a UserResponseDto instance.
     *
     * @return UserPreferenceResponseDto Returns a new instance of UserResponseDto
     *                         populated with the set properties.
     */
    public function build(): UserPreferenceResponseDto
    {
        return new UserPreferenceResponseDto($this->id, $this->source, $this->category,$this->authors);
    }
}