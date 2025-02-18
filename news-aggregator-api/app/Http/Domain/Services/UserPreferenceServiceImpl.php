<?php
namespace App\Http\Domain\Services;

use App\Http\Domain\Dtos\NewsSourceResponseDto;
use App\Http\Domain\Dtos\UserPreferenceResponseDto;
use App\Http\Services\UserPreferenceService;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\NewsSource;

/**
 * Class UserPreferenceServiceImpl
 *
 * Provides methods for managing user preferences, including retrieving,
 * saving, and accessing preferred sources for the authenticated user.
 */
class UserPreferenceServiceImpl implements UserPreferenceService
{
    /**
     * Get the preferences of the authenticated user.
     *
     * Retrieves the user preferences associated with the currently logged-in user.
     *
     * @return UserPreferenceResponseDto|null The user's preferences, or null if none exist.
     */
    public function getPreferences()
    {
        $userPreference = Auth::user()->preference;

        if ($userPreference) {
            // Use the builder to create and return a UserPreferenceResponseDto
            return UserPreferenceResponseDto::builder()
                ->setId($userPreference->id)
                ->setSource($userPreference->preferences['sources'])
                ->setAuthors($userPreference->preferences['authors'])
                ->setCategory($userPreference->preferences['categories'])
                ->build();
        }

        // Return null or a new instance with default values if no preferences exist
        return null;
    }

    /**
     * Save or update the preferences for the authenticated user.
     *
     * Uses the provided data to either create a new UserPreference record
     * or update an existing one for the currently logged-in user.
     *
     * @param array $data The data containing user preference information.
     * @return UserPreferenceResponseDto The saved or updated UserPreference instance.
     */
    public function savePreferences($data)
    {
        $preparedData = [
            'sources' => $data['preferences']['sources'] ?? null,
            'categories' => $data['preferences']['categories'] ?? null,
            'authors' => $data['preferences']['authors'] ?? null,
        ];

        // Update or create the user preference
        $userPreference = UserPreference::updateOrCreate(
            ['user_id' => auth()->id()],
            ['preferences' => $preparedData]
        );

        \Log::info($userPreference);

        return UserPreferenceResponseDto::builder()
            ->setId($userPreference->id)
            ->setSource($userPreference->preferences['sources'])
            ->setAuthors($userPreference->preferences['authors'])
            ->setCategory($userPreference->preferences['categories'])
            ->build();
    }

    /**
     * Get the preferred source for the authenticated user.
     *
     * Retrieves the user's preferences and returns the single preferred source.
     *
     * @return mixed The preferred source, or null if not set.
     */
    public function getPreferredSource()
    {
        $preference = $this->getPreferences();
        return $preference ? $preference->preferences['source'] : null;
    }

    /**
     * Retrieve all available news sources.
     *
     * This function fetches all records from the NewsSource model and returns them
     * as a JSON response. It is typically used in API endpoints to provide a list
     * of news sources that can be utilized in the application.
     *
     * @return Collection|NewsSource[] A collection of available news sources.
     */
    public function getAvailableSources()
    {
        return NewsSource::all()->map(function ($source) {
            return NewsSourceResponseDto::builder()
                ->setId($source->id)
                ->setName($source->name)
                ->build();
        });
    }
}