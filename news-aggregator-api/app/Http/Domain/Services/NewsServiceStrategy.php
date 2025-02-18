<?php
namespace App\Http\Domain\Services;

use App\Http\Services\NewsApi;

/**
 * Class NewsServiceStrategy
 *
 * Implements a strategy for fetching news articles using a specified news API.
 * This class relies on an implementation of the NewsApi to perform
 * the actual fetching of articles.
 */
class NewsServiceStrategy
{
    /**
     * @var NewsApi
     */
    protected $newsApi;

    /**
     * NewsServiceStrategy constructor.
     *
     * @param NewsApi $newsApi The news API implementation used to fetch articles.
     */
    public function __construct(NewsApi $newsApi)
    {
        $this->newsApi = $newsApi;
    }

    /**
     * Fetch articles from the news API.
     *
     * Calls the fetchArticles method on the news API implementation to retrieve
     * the latest articles.
     *
     * @return mixed The result of the fetchArticles method from the news API implementation.
     */
    public function fetchArticles()
    {
        return $this->newsApi->fetchArticles();
    }
}