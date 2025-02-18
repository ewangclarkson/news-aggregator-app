<?php

namespace App\Http\Domain\Dtos;

use App\Http\Domain\Dtos\Builders\LoginResponseDtoBuilder;

/**
 * Class LoginResponseDto
 *
 * Data Transfer Object (DTO) for login response data.
 *
 * This class encapsulates the response data returned upon successful
 * user login, including a JWT token, a refresh token, and the expiration
 * time of the token.
 *
 * @package App\Http\Domain\Dtos
 */
class LoginResponseDto
{
    public $token;
    public $refreshToken;
    public $expiresIn;
    public $user;

    /**
     * LoginResponseDto constructor.
     *
     * @param mixed $token The JWT token for the authenticated user.
     * @param mixed $refresh_token The refresh token for the user session.
     * @param mixed $expires_in The expiration time in seconds for the token.
     */
    public function __construct($token, $refresh_token, $expires_in, $user)
    {
        $this->token = $token;
        $this->refreshToken = $refresh_token;
        $this->expiresIn = $expires_in;
        $this->user = $user;
    }

    /**
     * Create a new instance of the LoginResponseDtoBuilder.
     *
     * @return LoginResponseDtoBuilder Returns a new instance of the builder
     *                                  for constructing LoginResponseDto.
     */
    public static function builder()
    {
        return new LoginResponseDtoBuilder();
    }
}