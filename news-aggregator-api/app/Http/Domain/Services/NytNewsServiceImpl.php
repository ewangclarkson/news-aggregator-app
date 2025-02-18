<?php
namespace App\Http\Domain\Services;

use App\Http\Domain\Services\pipeline\NytArticlesPipeline;
use App\Http\Domain\Services\pipeline\NytFetchArticlesStage;
use App\Http\Domain\Services\pipeline\NytProcessArticlesStage;
use App\Http\Services\NewsApi;
use GuzzleHttp\Client;


/**
 * Class NytNewsServiceImpl
 *
 * Implements the NewsApi to fetch articles from the NewsCred API.
 * This class utilizes GuzzleHttp\Client to make HTTP requests and updates
 * the articles in the local database using the Article model.
 */
class NytNewsServiceImpl implements NewsApi
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * NytNewsServiceImpl constructor.
     *
     * Initializes the Guzzle HTTP client used for making API requests.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Fetch articles from the NewsCred API and save them to the database.
     *
     * Makes a request to the NewsCred API to retrieve articles, decodes the
     * JSON response, and updates or creates Article records in the database
     * based on the fetched data.
     *
     * @return void
     */
    public function fetchArticles()
    {
        $pipeline = new NytArticlesPipeline();

        $pipeline->addStage(new NytFetchArticlesStage($this->client))
            ->addStage(new NytProcessArticlesStage());

        $pipeline->process(null); // You can pass any initial request data if needed.
    }
}