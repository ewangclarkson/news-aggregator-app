<?php

namespace App\Http\Domain\Dtos\Builders;

use App\Http\Domain\Dtos\UserResponseDto;

/**
 * Class UserResponseDtoBuilder
 *
 * A builder class for constructing instances of UserResponseDto.
 *
 * This class provides a fluent interface for setting the properties
 * of the UserResponseDto and creating an instance of it.
 *
 * @package App\Http\Domain\Dtos\Builders
 */
class UserResponseDtoBuilder
{
    private $id;
    private $name;
    private $email;

    /**
     * Set the ID of the user.
     *
     * @param int $id The unique identifier for the user.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set the name of the user.
     *
     * @param string $name The name of the user.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the email of the user.
     *
     * @param string $email The email of the user.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Build and return a UserResponseDto instance.
     *
     * @return UserResponseDto Returns a new instance of UserResponseDto
     *                         populated with the set properties.
     */
    public function build(): UserResponseDto
    {
        return new UserResponseDto($this->id, $this->name, $this->email);
    }
}