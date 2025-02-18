<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class AuthController
 *
 * Handles authentication-related actions such as registration, login, and logout.
 */
class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService The authentication service implementation.
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * Validates the incoming request data, registers the user,
     * and returns a success message.
     *
     * @param Request $request The request containing user registration data.
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Validate incoming request data
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:6',
            ]);

            // Call the auth service to register the user
            $this->authService->register($request->only('name', 'email', 'password'));

            // Return a success response
            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (Exception $e) {
            // Log the exception for debugging
            \Log::error('Registration error: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'Registration failed. Please try again.'], 500);
        }
    }

    /**
     * Log in a user.
     *
     * Validates the incoming request data, logs in the user,
     * and returns an authentication token if successful.
     *
     * @param Request $request The request containing user login data.
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Validate incoming request data
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            // Attempt to log in the user
            $tokens = $this->authService->login($request->only('email', 'password'));

            // Check if login was successful
            if (!$tokens) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Return the authentication token
            return response()->json($tokens);
        } catch (Exception $e) {
            // Log the exception for debugging
            \Log::error('Login error: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'Login failed. Please try again.'], 500);
        }
    }

    /**
     * Log out the authenticated user.
     *
     * Calls the auth service to log out the user
     * and returns a success message.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            // Call the auth service to log out the user
            $this->authService->logout();

            // Return a success response
            return response()->json(['message' => 'User logged out']);
        } catch (Exception $e) {
            // Log the exception for debugging
            \Log::error('Logout error: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'Logout failed. Please try again.'], 500);
        }
    }

    /**
     * Refresh the JWT token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $refreshToken = $request->input('refreshToken');

            $tokens = $this->authService->refreshToken($refreshToken);

            if (!$tokens) {
                return response()->json(['error' => 'Refresh token is invalid or expired'], 401);
            }

            return response()->json($tokens);
        } catch (Exception $e) {
            \Log::error('Token refresh error: ' . $e->getMessage());
            return response()->json(['error' => 'Token refresh failed. Please try again.'], 500);
        }
    }
}