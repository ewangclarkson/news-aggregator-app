<?php
namespace App\Http\Services;

use App\Models\UserPreference;

/**
 * Interface UserPreferenceService
 *
 * Defines the contract for managing user preferences, including methods for
 * retrieving, saving, and accessing preferred sources for authenticated users.
 */
interface UserPreferenceService
{
    /**
     * Get the preferences of the authenticated user.
     *
     * @return UserPreference|null The user's preferences, or null if none exist.
     */
    public function getPreferences();

    /**
     * Save or update the preferences for the authenticated user.
     *
     * @param array $data The data containing user preference information.
     * @return UserPreference The saved or updated UserPreference instance.
     */
    public function savePreferences(array $data);

    /**
     * Get the preferred source for the authenticated user.
     *
     * @return mixed The preferred source, or null if not set.
     */
    public function getPreferredSource();


    /**
     * Get the available news sources configured
     * @return mixed
     */
    public function getAvailableSources();
}