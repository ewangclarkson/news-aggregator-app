<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * Interface AuthService
 *
 * Defines the contract for authentication services, including user registration,
 * login, and logout using JWT (JSON Web Tokens).
 */
interface AuthService
{
    /**
     * Register a new user.
     *
     * @param array $data The data containing user registration information.
     * @return User The created User instance.
     */
    public function register(array $data);

    /**
     * Log in a user.
     *
     * @param array $credentials The user's credentials (email and password).
     * @return JsonResponse|null The JWT token if authentication is successful; null otherwise.
     */
    public function login(array $credentials);

    /**
     * Log out the authenticated user.
     *
     * @return JsonResponse
     */
    public function logout();

    /**
     * Refresh the JWT token.
     *
     * @param string $refreshToken The refresh token.
     * @return JsonResponse|null The new JWT token if refresh is successful; null otherwise.
     */
    public function refreshToken(string $refreshToken);
}
