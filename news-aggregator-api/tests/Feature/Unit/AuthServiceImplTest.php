<?php

namespace Tests\Feature\Unit;

use App\Http\Domain\Dtos\LoginResponseDto;
use App\Http\Domain\Dtos\UserResponseDto;
use App\Http\Domain\Services\AuthServiceImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AuthServiceImplTest extends TestCase
{
    use RefreshDatabase;
    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthServiceImpl();
    }

    public function testRegister()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
        ];

        $user = $this->authService->register($data);

        $this->assertInstanceOf(UserResponseDto::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertIsInt($user->id);
    }

    public function testLoginSuccess()
    {
        // First, create a user to log in
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
        ];
        $this->authService->register($data);

        // Set up credentials
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        $result = $this->authService->login($credentials);

        $this->assertNotNull($result);
        $this->assertInstanceOf(LoginResponseDto::class, $result);
        $this->assertNotNull($result->token);
        $this->assertNotNull($result->refreshToken);

        // Check that expiresIn is a date (instance of Carbon)
        $this->assertInstanceOf(\Carbon\Carbon::class, $result->expiresIn);
        $this->assertGreaterThan(now(), $result->expiresIn); // Ensure it's a future date
        $this->assertIsString($result->token);
        $this->assertIsString($result->refreshToken);
    }

    public function testLoginFailure()
    {
        $credentials = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ];

        $token = $this->authService->login($credentials);

        $this->assertNull($token);
    }

    public function testLogout()
    {
        // Mock the JWTAuth facade
        JWTAuth::shouldReceive('invalidate')->once();
        JWTAuth::shouldReceive('getToken')->andReturn('mocked-token');

        $this->authService->logout();
    }

    public function testRefreshToken()
    {
        // First, create a user and log them in to get the refresh token
        $data = [
            'name' => 'Alice Doe',
            'email' => 'alice@example.com',
            'password' => 'password123',
        ];
        $this->authService->register($data);
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];
        $result = $this->authService->login($credentials);

        // Simulate the refresh token process
        $refreshToken = $result->refreshToken;

        // Call the refreshToken method
        $newTokens = $this->authService->refreshToken($refreshToken);

        // Assert that new tokens are returned
        $this->assertNotNull($newTokens);
        $this->assertInstanceOf(LoginResponseDto::class, $newTokens);
        $this->assertNotNull($newTokens->token);
        $this->assertNotNull($newTokens->refreshToken);

        // Ensure expiresIn is an integer representing the time in seconds
        $this->assertInstanceOf(\Carbon\Carbon::class, $result->expiresIn);
        $this->assertGreaterThan(now(), $result->expiresIn); // Ensure it's a future date
        $this->assertIsString($newTokens->token);
        $this->assertIsString($newTokens->refreshToken);
    }
}