<?php

namespace Tests\Feature\Unit;

use App\Http\Domain\Services\ArticleServiceImpl;
use App\Http\Services\UserPreferenceService;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;
use App\Http\Domain\Dtos\PaginationResponseDto;

class ArticleServiceImplTest extends TestCase
{
    use RefreshDatabase;

    protected $articleService;
    protected $userPreferenceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userPreferenceService = Mockery::mock(UserPreferenceService::class);
        $this->articleService = new ArticleServiceImpl($this->userPreferenceService);
    }

    public function testSearchArticlesWithKeyword()
    {
        // Create sample articles with content
        Article::factory()->create([
            'title' => 'Laravel Basics',
            'category' => 'Programming',
            'content' => 'Introduction to Laravel',
            'author' => 'John Doe',
            'description' => 'A comprehensive guide to Laravel.',
            'link' => 'https://example.com/laravel-basics',
            'image' => 'https://example.com/images/laravel-basics.jpg',
            'published_at' => now(),
        ]);
        Article::factory()->create([
            'title' => 'Advanced Laravel',
            'category' => 'Programming',
            'content' => 'Deep dive into Laravel features',
            'author' => 'Jane Smith',
            'description' => 'Advanced topics in Laravel development.',
            'link' => 'https://example.com/advanced-laravel',
            'image' => 'https://example.com/images/advanced-laravel.jpg',
            'published_at' => now(),
        ]);
        Article::factory()->create([
            'title' => 'PHP for Beginners',
            'category' => 'Programming',
            'content' => 'Learn PHP basics',
            'author' => 'Alex Brown',
            'description' => 'Start your journey with PHP.',
            'link' => 'https://example.com/php-for-beginners',
            'image' => 'https://example.com/images/php-for-beginners.jpg',
            'published_at' => now(),
        ]);

        // Create a request with a keyword
        $request = new Request(['keyword' => 'Laravel']);

        // Search for articles
        $paginationResult = $this->articleService->search($request);

        // Assert that two articles match the keyword
        $this->assertCount(2, $paginationResult->data);
        $this->assertEquals('Laravel Basics', $paginationResult->data[0]->title);
        $this->assertEquals('Advanced Laravel', $paginationResult->data[1]->title);
        $this->assertEquals(1, $paginationResult->meta['currentPage']);
        $this->assertEquals(1, $paginationResult->meta['totalPages']);
    }

    public function testSearchArticlesWithCategory()
    {
        // Create sample articles with content
        Article::factory()->create([
            'title' => 'Laravel Basics',
            'category' => 'Programming',
            'content' => 'Introduction to Laravel',
            'author' => 'John Doe',
            'description' => 'A comprehensive guide to Laravel.',
            'link' => 'https://example.com/laravel-basics',
            'image' => 'https://example.com/images/laravel-basics.jpg',
            'published_at' => now(),
        ]);
        Article::factory()->create([
            'title' => 'Health Tips',
            'category' => 'Health',
            'content' => 'How to stay healthy',
            'author' => 'Dr. Healthy',
            'description' => 'Essential health tips for everyone.',
            'link' => 'https://example.com/health-tips',
            'image' => 'https://example.com/images/health-tips.jpg',
            'published_at' => now(),
        ]);
        Article::factory()->create([
            'title' => 'PHP for Beginners',
            'category' => 'Programming',
            'content' => 'Learn PHP basics',
            'author' => 'Alex Brown',
            'description' => 'Start your journey with PHP.',
            'link' => 'https://example.com/php-for-beginners',
            'image' => 'https://example.com/images/php-for-beginners.jpg',
            'published_at' => now(),
        ]);

        // Create a request with a category
        $request = new Request(['category' => ['Health']]);

        // Search for articles
        $paginationResult = $this->articleService->search($request);

        // Assert that one article matches the category
        $this->assertCount(1, $paginationResult->data);
        $this->assertEquals('Health Tips', $paginationResult->data[0]->title);
        $this->assertEquals(1, $paginationResult->meta['currentPage']);
        $this->assertEquals(1, $paginationResult->meta['totalPages']);
    }

    public function testSearchArticlesWithSource()
    {
        // Create sample articles with content
        Article::factory()->create([
            'title' => 'Laravel Basics',
            'source' => 'source1',
            'content' => 'Introduction to Laravel',
            'author' => 'John Doe',
            'description' => 'A comprehensive guide to Laravel.',
            'link' => 'https://example.com/laravel-basics',
            'image' => 'https://example.com/images/laravel-basics.jpg',
            'published_at' => now(),
        ]);
        Article::factory()->create([
            'title' => 'Advanced Laravel',
            'source' => 'source2',
            'content' => 'Deep dive into Laravel features',
            'author' => 'Jane Smith',
            'description' => 'Advanced topics in Laravel development.',
            'link' => 'https://example.com/advanced-laravel',
            'image' => 'https://example.com/images/advanced-laravel.jpg',
            'published_at' => now(),
        ]);
        Article::factory()->create([
            'title' => 'PHP for Beginners',
            'source' => 'source1',
            'content' => 'Learn PHP basics',
            'author' => 'Alex Brown',
            'description' => 'Start your journey with PHP.',
            'link' => 'https://example.com/php-for-beginners',
            'image' => 'https://example.com/images/php-for-beginners.jpg',
            'published_at' => now(),
        ]);

        // Create a request with a specific source
        $request = new Request(['source' => ['source2']]);

        // Search for articles
        $paginationResult = $this->articleService->search($request);

        // Assert that one article matches the source
        $this->assertCount(1, $paginationResult->data);
        $this->assertEquals('Advanced Laravel', $paginationResult->data[0]->title);
        $this->assertEquals(1, $paginationResult->meta['currentPage']);
        $this->assertEquals(1, $paginationResult->meta['totalPages']);
    }

    public function testSearchArticlesWithMultipleFilters()
    {
        // Create sample articles with content
        Article::factory()->create([
            'title' => 'Laravel Basics',
            'category' => 'Programming',
            'source' => 'source1',
            'content' => 'Introduction to Laravel',
            'author' => 'John Doe',
            'description' => 'A comprehensive guide to Laravel.',
            'link' => 'https://example.com/laravel-basics',
            'image' => 'https://example.com/images/laravel-basics.jpg',
            'published_at' => now(),
        ]);
        Article::factory()->create([
            'title' => 'Advanced Laravel',
            'category' => 'Programming',
            'source' => 'source2',
            'content' => 'Deep dive into Laravel features',
            'author' => 'Jane Smith',
            'description' => 'Advanced topics in Laravel development.',
            'link' => 'https://example.com/advanced-laravel',
            'image' => 'https://example.com/images/advanced-laravel.jpg',
            'published_at' => now(),
        ]);
        Article::factory()->create([
            'title' => 'PHP for Beginners',
            'category' => 'Programming',
            'source' => 'source1',
            'content' => 'Learn PHP basics',
            'author' => 'Alex Brown',
            'description' => 'Start your journey with PHP.',
            'link' => 'https://example.com/php-for-beginners',
            'image' => 'https://example.com/images/php-for-beginners.jpg',
            'published_at' => now(),
        ]);

        // Create a request with multiple filters
        $request = new Request([
            'keyword' => 'Laravel',
            'category' => ['Programming'],
            'source' => ['source1'],
        ]);

        // Search for articles
        $paginationResult = $this->articleService->search($request);

        // Assert that one article matches the filters
        $this->assertCount(1, $paginationResult->data);
        $this->assertEquals('Laravel Basics', $paginationResult->data[0]->title);
        $this->assertEquals(1, $paginationResult->meta['currentPage']);
        $this->assertEquals(1, $paginationResult->meta['totalPages']);
    }
}