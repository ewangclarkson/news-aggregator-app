<?php

namespace App\Http\Domain\Dtos\Builders;

use App\Http\Domain\Dtos\LoginResponseDto;

/**
 * Class LoginResponseDtoBuilder
 *
 * A builder class for constructing instances of LoginResponseDto.
 *
 * This class provides a fluent interface for setting the properties
 * of the LoginResponseDto and creating an instance of it.
 *
 * @package App\Http\Domain\Dtos\Builders
 */
class LoginResponseDtoBuilder
{
    private $token;
    private $refresh_token;
    private $expires_in;
    private $user;

    /**
     * Set the token.
     *
     * @param mixed $token The JWT token for the user.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Set the refresh token.
     *
     * @param mixed $refresh_token The refresh token for the user session.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setRefreshToken($refresh_token)
    {
        $this->refresh_token = $refresh_token;
        return $this;
    }

    /**
     * Set the expiration time.
     *
     * @param mixed $expires_in The expiration time in seconds for the token.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setExpiresIn($expires_in)
    {
        $this->expires_in = $expires_in;
        return $this;
    }

    /**
     * Set the expiration time.
     *
     * @param $user - Auth user details
     * @return $this Returns the builder instance for method chaining.
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }


    /**
     * Build and return a LoginResponseDto instance.
     *
     * @return LoginResponseDto Returns a new instance of LoginResponseDto
     *                         populated with the set properties.
     */
    public function build()
    {
        return new LoginResponseDto($this->token, $this->refresh_token, $this->expires_in, $this->user);
    }
}