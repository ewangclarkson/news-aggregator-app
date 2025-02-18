<?php

namespace Tests\Feature\Unit;

use App\Http\Domain\Dtos\UserPreferenceResponseDto;
use App\Http\Services\UserPreferenceService;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPreferenceServiceImplTest extends TestCase
{
    use RefreshDatabase;

    protected $userPreferenceService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the UserPreferenceService
        $this->userPreferenceService = \Mockery::mock(UserPreferenceService::class);
        $this->app->instance(UserPreferenceService::class, $this->userPreferenceService);
    }

    public function testIndexReturnsUserPreferences()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Log in to get a token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Arrange: Create a user preference
        $userPreference = UserPreference::factory()->create([
            'user_id' => $user->id,
            'preferences' => [
                'sources' => ['source1', 'source2'],
                'categories' => ['category1', 'category2'],
                'authors' => ['author1', 'author2'],
            ],
        ]);

        // Expect the getPreferences method to be called and return the user's preferences
        $this->userPreferenceService->shouldReceive('getPreferences')
            ->once()
            ->andReturn(
                UserPreferenceResponseDto::builder()
                    ->setId($userPreference->id)
                    ->setSource($userPreference->preferences['sources'])
                    ->setCategory($userPreference->preferences['categories'])
                    ->setAuthors($userPreference->preferences['authors'])
                    ->build()
            );

        // Act: Make a request to the index method with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user/preferences');

        // Assert: Check the response
        $response->assertStatus(200)
            ->assertJson([
                'id' => $userPreference->id,
                'source' => $userPreference->preferences['sources'],
                'category' => $userPreference->preferences['categories'],
                'authors' => $userPreference->preferences['authors'],
            ]);
    }

    public function testStoreSavesUserPreferences()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Log in to get a token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Arrange: Prepare mock data
        $mockPreferences = [
            'preferences' => [
                'sources' => ['source1', 'source2'],
                'categories' => ['category1', 'category2'],
                'authors' => ['author1', 'author2'],
            ],
        ];

        // Expect the savePreferences method to be called and return the saved preferences
        $this->userPreferenceService->shouldReceive('savePreferences')
            ->once()
            ->with($mockPreferences)
            ->andReturn(
                UserPreferenceResponseDto::builder()
                    ->setId(1) // Assuming the ID of the created preference
                    ->setSource($mockPreferences['preferences']['sources'])
                    ->setCategory($mockPreferences['preferences']['categories'])
                    ->setAuthors($mockPreferences['preferences']['authors'])
                    ->build()
            );

        // Act: Make a request to store user preferences with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/user/preferences', $mockPreferences);

        // Assert: Check the response
        $response->assertStatus(201)
            ->assertJson([
                'id' => 1, // Check the ID returned
                'source' => $mockPreferences['preferences']['sources'],
                'category' => $mockPreferences['preferences']['categories'],
                'authors' => $mockPreferences['preferences']['authors'],
            ]);
    }

    public function testAvailableNewsSourcesReturnsSources()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Log in to get a token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Arrange: Prepare mock data
        $mockSources = [
            ['id' => 1, 'name' => 'Source 1'],
            ['id' => 2, 'name' => 'Source 2'],
        ];

        // Expect the getAvailableSources method to be called and return the mock sources
        $this->userPreferenceService->shouldReceive('getAvailableSources')
            ->once()
            ->andReturn($mockSources);

        // Act: Make a request to retrieve available news sources with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/news/sources');

        // Assert: Check the response
        $response->assertStatus(200)
            ->assertJson($mockSources);
    }

    public function testIndexHandlesErrorGracefully()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Log in to get a token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Expect the getPreferences method to throw an exception
        $this->userPreferenceService->shouldReceive('getPreferences')
            ->once()
            ->andThrow(new \Exception('Service error'));

        // Act: Make a request to the index method with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user/preferences');

        // Assert: Check the response
        $response->assertStatus(500)
            ->assertJson(['error' => 'Unable to retrieve user preferences. Please try again later.']);
    }

    public function testStoreHandlesErrorGracefully()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Log in to get a token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Arrange: Prepare invalid data
        $invalidPreferences = [
            'preferences' => [
                'sources' => null,
                'categories' => null,
                'authors' => null,
            ],
        ];

        // Expect the savePreferences method to throw an exception
        $this->userPreferenceService->shouldReceive('savePreferences')
            ->once()
            ->with($invalidPreferences)
            ->andThrow(new \Exception('Validation error'));

        // Act: Make a request to store user preferences with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/user/preferences', $invalidPreferences);

        // Assert: Check the response
        $response->assertStatus(500)
            ->assertJson(['error' => 'Unable to save user preferences. Please try again later.']);
    }

    public function testAvailableNewsSourcesHandlesErrorGracefully()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Log in to get a token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Expect the getAvailableSources method to throw an exception
        $this->userPreferenceService->shouldReceive('getAvailableSources')
            ->once()
            ->andThrow(new \Exception('Service error'));

        // Act: Make a request to retrieve available news sources with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/news/sources');

        // Assert: Check the response
        $response->assertStatus(500)
            ->assertJson(['error' => 'Unable to retrieve news sources. Please try again later.']);
    }
}