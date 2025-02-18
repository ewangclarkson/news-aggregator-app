<?php
namespace App\Http\Domain\Services;

use App\Http\Domain\Dtos\LoginResponseDto;
use App\Http\Domain\Dtos\UserResponseDto;
use App\Http\Services\AuthService;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthServiceImpl
 *
 * Provides authentication services such as user registration, login, and logout
 * using JWT (JSON Web Tokens) for session management.
 */
class AuthServiceImpl implements AuthService
{
    /**
     * Register a new user.
     *
     * Creates a new user in the database with the provided data.
     * The password is hashed before storing.
     *
     * @param array $data The data containing user registration information.
     * @return UserResponseDto The created User instance.
     */
    public function register($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return UserResponseDto::builder()
            ->setId($user->id)
            ->setName($user->name)
            ->setEmail($user->email)
            ->build();
    }

    /**
     * Log in a user.
     *
     * Attempts to authenticate a user with the provided credentials.
     * If successful, returns a JWT token; otherwise, returns null.
     *
     * @param array $credentials The user's credentials (email and password).
     * @return LoginResponseDto The JWT token if authentication is successful; null otherwise.
     */
    public function login($credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        $refreshToken = $this->createRefreshToken($user);
        $expiresAt = now()->addMinutes(config('jwt.ttl'));

        return LoginResponseDto::builder()
            ->setToken($token)
            ->setRefreshToken($refreshToken)
            ->setExpiresIn($expiresAt)
            ->setUser(new UserResponseDto($user->id, $user->name, $user->email))
            ->build();
    }

    /**
     * Log out the authenticated user.
     *
     * Invalidates the current JWT token, effectively logging out the user.
     *
     * @return void
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Refreshes the JWT token using the provided refresh token.
     *
     * This method checks if the provided refresh token is valid and not expired.
     * If valid, it generates a new JWT token and a new refresh token for the user,
     * invalidates the old refresh token, and returns both tokens along with their expiration time.
     *
     * @param string $refreshToken The refresh token to validate and use for generating a new JWT token.
     * @return LoginResponseDto An array containing the new JWT token, new refresh token, and expiration time,
     *                   or null if the refresh token is invalid or expired.
     */
    public function refreshToken(string $refreshToken)
    {
        $tokenRecord = RefreshToken::where('refresh_token', $refreshToken)->first();
        if (!$tokenRecord || $tokenRecord->expires_at < now()) {
            return null; // Refresh token not found or expired
        }
        $user = $tokenRecord->user;

        if (!$user) {
            return null;
        }

        $token = JWTAuth::fromUser($user);
        $newRefreshToken = $this->createRefreshToken($user);
        $tokenRecord->delete();
        $expiresAt = now()->addMinutes(config('jwt.ttl'));

        return LoginResponseDto::builder()
            ->setToken($token)
            ->setRefreshToken($newRefreshToken)
            ->setExpiresIn($expiresAt) // Token expiration time in seconds
            ->setUser($user)
            ->build();
    }

    /**
     * Creates a new refresh token for the specified user.
     *
     * This method generates a unique refresh token and stores it in the database
     * linked to the user, along with its expiration time.
     *
     * @param User $user The user for whom the refresh token is being created.
     * @return string The generated refresh token.
     */
    private function createRefreshToken(User $user)
    {
        // Generate a unique refresh token
        $refreshToken = Str::random(60);

        // Create a new refresh token record using the RefreshToken model
        RefreshToken::create([
            'user_id' => $user->id,
            'refresh_token' => $refreshToken,
            'expires_at' => now()->addDays(30), // Set an expiration time
        ]);

        return $refreshToken;
    }
}