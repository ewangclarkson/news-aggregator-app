<?php

namespace App\Http\Domain\Services;

use App\Http\Domain\Dtos\ArticleResponseDto;
use App\Http\Domain\Dtos\Builders\PaginationResponseDtoBuilder;
use App\Http\Domain\Dtos\CategoriesAndAuthorsResponseDto;
use App\Http\Domain\Dtos\PaginationResponseDto;
use App\Http\Domain\Dtos\UserPreferenceResponseDto;
use App\Http\Services\ArticleService;
use App\Http\Services\UserPreferenceService;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ArticleServiceImpl
 *
 * Provides services related to articles, including searching for articles
 * based on various criteria.
 */
class ArticleServiceImpl implements ArticleService
{
    const ITEMS_PER_PAGE = 50;

    protected $userPreferenceService;

    /**
     * ArticleServiceImpl constructor.
     *
     * @param UserPreferenceService $userPreferenceService The user preference service.
     */
    public function __construct(UserPreferenceService $userPreferenceService)
    {
        $this->userPreferenceService = $userPreferenceService;
    }

    /**
     * Search for articles based on the provided request parameters.
     *
     * Builds a query to search for articles, filtering by title keyword,
     * category, source, and author if specified in the request.
     *
     * @param Request $request The incoming request containing search parameters.
     * @return PaginationResponseDto A collection of articles matching the search criteria.
     */
    public function search(Request $request)
    {
        $query = Article::query();

        // Apply filters based on request parameters
        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category')) {
            $query->whereIn('category', $request->category);
        }

        if ($request->filled('source')) {
            $query->whereIn('source', $request->source);
        }

        if ($request->filled('author')) {
            $query->whereIn('author', $request->author);
        }

        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('published_at', [$request->startDate, $request->endDate]);
        }

        // Pagination with page number from request
        $page = $request->input('page', 1); // Default to page 1 if not provided
        $result = $query->paginate(env('PAGINATION_LIMIT'), ['*'], 'page', $page);

        // Transform articles into DTOs
        $articles = $result->getCollection()->map(function ($article) {
            return ArticleResponseDto::builder()
                ->setId($article->id)
                ->setTitle($article->title)
                ->setContent($article->content)
                ->setCategory($article->category)
                ->setSource($article->source)
                ->setAuthor($article->author)
                ->setDescription($article->description)
                ->setLink($article->link)
                ->setImage($article->image)
                ->setPublishedAt($article->published_at)
                ->build();
        });

        // Construct and return the pagination response
        return PaginationResponseDto::builder()
            ->setData($articles->toArray())
            ->setPagination(
                $result->currentPage(),
                $result->lastPage(),
                $result->total(),
                $result->perPage()
            )
            ->build();
    }

    /**
     * Retrieve articles based on user preferences.
     *
     * This method fetches articles according to the user's saved preferences
     * for sources, categories, and authors.
     *
     * @return PaginationResponseDto A collection of articles based on user preferences.
     */
    public function get()
    {
        $query = Article::query();
        $preferences = Auth::user()->preference;

        // Apply filters based on user preferences
        if ($preferences) {
            $prefs = $preferences->preferences;

            if (!empty($prefs['sources'])) {
                $query->whereIn('source', $prefs['sources']);
            }
            if (!empty($prefs['categories'])) {
                $query->whereIn('category', $prefs['categories']);
            }
            if (!empty($prefs['authors'])) {
                $query->whereIn('author', $prefs['authors']);
            }
        }

        // Pagination
        $result = $query->paginate(env('PAGINATION_LIMIT'), ['*'], 'page', 1);

        // Transform articles into DTOs
        $articles = $result->getCollection()->map(function ($article) {
            return ArticleResponseDto::builder()
                ->setId($article->id)
                ->setTitle($article->title)
                ->setContent($article->content)
                ->setCategory($article->category)
                ->setSource($article->source)
                ->setAuthor($article->author)
                ->setDescription($article->description)
                ->setLink($article->link)
                ->setImage($article->image)
                ->setPublishedAt($article->published_at)
                ->build();
        });

        // Construct and return the pagination response
        return PaginationResponseDto::builder()
            ->setData($articles->toArray())
            ->setPagination(
                $result->currentPage(),
                $result->lastPage(),
                $result->total(),
                $result->perPage()
            )
            ->build();
    }

    /**
     * Retrieve unique categories and authors from the articles.
     *
     * This method queries the database for distinct categories and authors
     * associated with articles. It constructs a Data Transfer Object (DTO)
     * to encapsulate the results and returns them as a JSON response.
     *
     * The response structure will include two arrays:
     * - `categories`: The unique categories retrieved from the articles.
     * - `authors`: The unique authors retrieved from the articles.
     *
     * @return CategoriesAndAuthorsResponseDto A JSON response containing the unique categories and authors.
     */
    public function getUniqueCategoriesAndAuthors()
    {
        $categories = Article::distinct('category')->pluck('category')->toArray();
        $authors = Article::distinct('author')->pluck('author')->toArray();

        // Fetch user preferences
        $userPreference = Auth::user()->preference;

        $preferences = UserPreferenceResponseDto::builder()
            ->setId($userPreference->id ?? null)
            ->setSource($userPreference->preferences['sources'] ?? [])
            ->setAuthors($userPreference->preferences['authors'] ?? [])
            ->setCategory($userPreference->preferences['categories'] ?? [])
            ->build();

        return CategoriesAndAuthorsResponseDto::builder()
            ->setCategories($categories)
            ->setAuthors($authors)
            ->setUserPreference($preferences)
            ->build();
    }
}