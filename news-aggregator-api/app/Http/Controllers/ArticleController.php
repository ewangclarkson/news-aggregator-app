<?php

namespace App\Http\Controllers;

use App\Http\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class ArticleController
 *
 * Handles article-related HTTP requests, providing methods to search for articles
 * based on user-defined criteria. It interacts with the ArticleService
 * to perform these operations.
 *
 * @package App\Http\Controllers
 */
class ArticleController extends Controller
{
    /**
     * @var ArticleService
     */
    protected $articleService;

    /**
     * ArticleController constructor.
     *
     * Initializes the controller with the specified ArticleService.
     *
     * @param ArticleService $articleService The service for managing articles.
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Display a listing of articles based on user preferences.
     *
     * Retrieves articles that match the user's preferences. Returns the articles
     * as a JSON response. This method handles the retrieval of articles
     * without any specific search criteria.
     *
     * @param Request $request The incoming request containing optional parameters.
     * @return JsonResponse A JSON response containing the articles or an error message.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Attempt to fetch articles based on user preferences
            $articles = $this->articleService->get();
            return response()->json($articles, 200);
        } catch (Exception $e) {
            // Log the exception for debugging (optional)
            \Log::error('Error fetching articles: ' . $e->getMessage());

            // Return a JSON response with an error message and a 500 status code
            return response()->json([
                'error' => 'Unable to fetch articles. Please try again later.',
                'message' => $e->getMessage(), // Optionally include the exception message for debugging
            ], 500);
        }
    }

    /**
     * Search for articles based on the provided request parameters.
     *
     * This method retrieves articles that match the search criteria specified
     * in the incoming request. It allows users to filter articles based on
     * various attributes like title, category, source, and more.
     *
     * @param Request $request The incoming request containing search parameters.
     * @return JsonResponse A JSON response containing the articles matching the search criteria,
     *                      or an error message if an exception occurs.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            // Attempt to search for articles based on request parameters
            $articles = $this->articleService->search($request);
            return response()->json($articles, 200);
        } catch (Exception $e) {
            // Log the exception for debugging (optional)
            \Log::error('Error fetching articles: ' . $e->getMessage());

            // Return a JSON response with an error message and a 500 status code
            return response()->json([
                'error' => 'Unable to fetch articles. Please try again later.',
                'message' => $e->getMessage(), // Optionally include the exception message for debugging
            ], 500);
        }
    }


    /**
     * Retrieve unique categories and authors from the articles.
     *
     * This method fetches distinct categories and authors associated with
     * articles in the database. It is designed to provide a consolidated
     * response containing both sets of data, which can be used for
     * filtering articles or populating dropdowns in the frontend.
     *
     * @param Request $request The incoming request instance.
     *
     * @return JsonResponse A JSON response containing:
     * }
     */
    public function getUniqueCategoriesAndAuthors(Request $request): JsonResponse
    {
        try {
            // Attempt to search for articles based on request parameters
            $authorsAndCategories = $this->articleService->getUniqueCategoriesAndAuthors();
            return response()->json($authorsAndCategories, 200);
        } catch (Exception $e) {
            // Log the exception for debugging (optional)
            \Log::error('Error fetching articles: ' . $e->getMessage());

            // Return a JSON response with an error message and a 500 status code
            return response()->json([
                'error' => 'Unable to fetch articles. Please try again later.',
                'message' => $e->getMessage(), // Optionally include the exception message for debugging
            ], 500);
        }
    }

}