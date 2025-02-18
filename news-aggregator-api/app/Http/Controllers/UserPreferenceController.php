<?php

namespace App\Http\Controllers;

use App\Http\Services\UserPreferenceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class UserPreferenceController
 *
 * Handles user preference-related HTTP requests, providing methods to
 * retrieve and save user preferences. It interacts with the
 * UserPreferenceService to perform these operations.
 */
class UserPreferenceController extends Controller
{
    /**
     * @var UserPreferenceService
     */
    protected $userPreferenceService;

    /**
     * UserPreferenceController constructor.
     *
     * Initializes the controller with the specified UserPreferenceService.
     *
     * @param UserPreferenceService $userPreferenceService The service for managing user preferences.
     */
    public function __construct(UserPreferenceService $userPreferenceService)
    {
        $this->userPreferenceService = $userPreferenceService;
    }

    /**
     * Display the authenticated user's preferences.
     *
     * Retrieves and returns the preferences associated with the currently logged-in user.
     *
     * @return JsonResponse The user's preferences as a JSON response.
     */
    public function index(): JsonResponse
    {
        try {
            $preferences = $this->userPreferenceService->getPreferences();

            return response()->json($preferences);
        } catch (Exception $e) {
            // Log the exception for debugging
            \Log::error('Error retrieving user preferences: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'Unable to retrieve user preferences. Please try again later.'], 500);
        }
    }

    /**
     * Store or update the authenticated user's preferences.
     *
     * Validates the incoming request data and saves or updates the user preferences
     * in the database. Returns the saved user preferences as a JSON response.
     *
     * @param Request $request The incoming request containing user preference data.
     * @return JsonResponse The response containing the saved user preferences.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $preferences = $request->validate([
                'preferences' => 'required|array',
                'preferences.sources' => 'nullable|array',
                'preferences.categories' => 'nullable|array',
                'preferences.authors' => 'nullable|array',
            ]);

            $userPreference = $this->userPreferenceService->savePreferences($preferences);

            return response()->json($userPreference, 201);
        } catch (Exception $e) {
            // Log the exception for debugging
            \Log::error('Error saving user preferences: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'Unable to save user preferences. Please try again later.'], 500);
        }
    }

    /**
     * Retrieve a list of available news sources.
     *
     * This method interacts with the user preference service to fetch
     * a list of news sources that are available for the user to select from.
     * If successful, it returns the list of news sources as a JSON response.
     * In case of an error, it logs the error message and returns
     * a JSON response with a user-friendly error message and a 500 status code.
     *
     * @return JsonResponse A JSON response containing the list of available news sources,
     *                      or an error message if the retrieval fails.
     */
    public function availableNewsSources(): JsonResponse
    {
        try {
            $available_news_sources = $this->userPreferenceService->getAvailableSources();

            return response()->json($available_news_sources);
        } catch (Exception $e) {
            // Log the exception for debugging
            \Log::error('Error retrieving news sources: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json(['error' => 'Unable to retrieve news sources. Please try again later.'], 500);
        }
    }
}