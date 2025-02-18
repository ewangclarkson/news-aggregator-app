<?php

namespace Tests\Feature\Integration;

use App\Http\Services\ArticleService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Http\Domain\Dtos\PaginationResponseDto;
use App\Http\Domain\Dtos\ArticleResponseDto;

class ArticleControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $articleService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the ArticleService
        $this->articleService = \Mockery::mock(ArticleService::class);
        $this->app->instance(ArticleService::class, $this->articleService);
    }

    public function testIndexReturnsArticles()
    {
        // Arrange: Prepare mock articles
        $mockArticles = [
            new ArticleResponseDto(1, 'Article 1', 'Content of Article 1', 'Category 1', 'Source 1', 'Author 1', 'Description 1', 'https://example.com/article1', 'https://example.com/image1.jpg', now()),
            new ArticleResponseDto(2, 'Article 2', 'Content of Article 2', 'Category 2', 'Source 2', 'Author 2', 'Description 2', 'https://example.com/article2', 'https://example.com/image2.jpg', now()),
        ];

        // Prepare mock pagination response
        $mockPaginationResponse = new PaginationResponseDto($mockArticles, [
            'currentPage' => 1,
            'totalPages' => 1,
            'totalItems' => 2,
            'itemsPerPage' => 2,
        ]);

        // Expect the get method to be called and return the mock pagination response
        $this->articleService->shouldReceive('get')
            ->once()
            ->andReturn($mockPaginationResponse);

        // Create and authenticate a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'), // Ensure the password is hashed
        ]);

        // Log in the user to get a token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Act: Make a request to the index method with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/articles');

        // Assert: Check the response status
        $response->assertStatus(200);

        // Assert: Check the structure of the response
        $response->assertJson([
            'meta' => [
                'currentPage' => 1,
                'totalPages' => 1,
                'totalItems' => 2,
                'itemsPerPage' => 2,
            ],
        ]);

        // Check each article's data structure
        foreach ($mockArticles as $mockArticle) {
            $response->assertJsonFragment([
                'id' => $mockArticle->id,
                'title' => $mockArticle->title,
                'content' => $mockArticle->content,
                'category' => $mockArticle->category,
                'source' => $mockArticle->source,
                'author' => $mockArticle->author,
                'description' => $mockArticle->description,
                'link' => $mockArticle->link,
                'image' => $mockArticle->image,
                // Do not assert 'published_at' directly to allow for timestamp differences
            ]);
        }
    }

    public function testIndexHandlesErrorGracefully()
    {
        // Arrange: Mock an exception being thrown by the service
        $this->articleService->shouldReceive('get')
            ->once()
            ->andThrow(new \Exception('Services error'));

        // Create and authenticate a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'), // Ensure the password is hashed
        ]);

        // Log in the user to get a token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Act: Make a request to the index method with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/articles');

        // Assert: Check the response
        $response->assertStatus(500)
            ->assertJson([
                'error' => 'Unable to fetch articles. Please try again later.',
                'message' => 'Services error',
            ]);
    }
}