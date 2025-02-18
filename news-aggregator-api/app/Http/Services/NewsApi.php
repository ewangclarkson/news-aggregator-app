<?php

namespace App\Http\Services;

/**
 * Interface NewsApi
 *
 * This interface defines the contract for a News API service.
 * Implementations of this interface should provide methods to interact
 * with a news API, specifically fetching articles.
 *
 * @package App\Http\Services
 */
interface NewsApi
{
    /**
     * Fetch articles from the news API.
     *
     * This method should be implemented to retrieve articles
     * from the news source. The implementation may specify
     * parameters for filtering or sorting the articles.
     *
     * @return mixed
     *   The fetched articles. The return type can be an array,
     *   a collection, or any other structure depending on the
     *   implementation.
     */
    public function fetchArticles();
}