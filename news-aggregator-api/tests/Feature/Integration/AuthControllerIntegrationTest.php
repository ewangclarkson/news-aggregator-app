<?php

namespace Tests\Feature\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegister()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User registered successfully']);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    public function testUserCanLogin()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'refreshToken', 'expiresIn', 'user']); // Check if token and refresh_token are returned
    }

    public function testUserCannotLoginWithInvalidCredentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        Auth::login($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'User logged out']);
    }

    public function testUserCanRefreshToken()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $refreshToken = $response->json('refreshToken');

        $response = $this->postJson('/api/refresh', ['refreshToken' => $refreshToken]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'refreshToken', 'expiresIn','user']); // Check if new tokens are returned
    }

    public function testUserCannotRefreshWithInvalidToken()
    {
        $response = $this->postJson('/api/refresh', ['refreshToken' => 'invalid_token']);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Refresh token is invalid or expired']);
    }
}