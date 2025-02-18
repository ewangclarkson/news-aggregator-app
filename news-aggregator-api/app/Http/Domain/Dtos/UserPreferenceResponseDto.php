<?php

namespace App\Http\Domain\Dtos;

use App\Http\Domain\Dtos\Builders\UserPreferenceResponseDtoBuilder;

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
class UserPreferenceResponseDto
{
    public $id;
    public $source;
    public $category;
    public $authors;

    /**
     * UserResponseDto constructor.
     *
     * @param $id - The unique identifier for the user.
     * @param $source - The name of the user.
     * @param $category - The category of the user.
     */
    public function __construct($id, $source, $category, $authors)
    {
        $this->id = $id;
        $this->source = $source;
        $this->category = $category;
        $this->authors = $authors;
    }

    /**
     * Create a new instance of the UserResponseDtoBuilder.
     *
     * @return UserPreferenceResponseDtoBuilder Returns a new instance of the builder
     *                                 for constructing UserResponseDto.
     */
    public static function builder(): UserPreferenceResponseDtoBuilder
    {
        return new UserPreferenceResponseDtoBuilder();
    }
}