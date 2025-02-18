<?php

namespace App\Http\Domain\Dtos;

use App\Http\Domain\Dtos\Builders\UserResponseDtoBuilder;

/**
 * Class UserResponseDto
 *
 * Data Transfer Object (DTO) for user registration response data.
 *
 * This class encapsulates the data structure for a user,
 * including the user's unique identifier, name, and email.
 *
 * @package App\Http\Domain\Dtos
 */
class UserResponseDto
{
    public $id;
    public $name;
    public $email;

    /**
     * UserResponseDto constructor.
     *
     * @param $id - The unique identifier for the user.
     * @param $name - The name of the user.
     * @param $email - The email of the user.
     */
    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Create a new instance of the UserResponseDtoBuilder.
     *
     * @return UserResponseDtoBuilder Returns a new instance of the builder
     *                                 for constructing UserResponseDto.
     */
    public static function builder(): UserResponseDtoBuilder
    {
        return new UserResponseDtoBuilder();
    }
}