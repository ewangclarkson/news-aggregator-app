<?php
namespace App\Http\Domain\Services\pipeline;

use GuzzleHttp\Client;

/**
 * Class NytFetchArticlesStage
 *
 * This class represents a stage in the article-fetching pipeline
 * that is responsible for retrieving articles from the New York
 * Times API. It uses GuzzleHttp\Client to make HTTP requests
 * and processes the API response to extract relevant article data.
 *
 * @package App\Http\Services\Implementation\pipeline
 */
class NytFetchArticlesStage
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * NytFetchArticlesStage constructor.
     *
     * @param Client $client An instance of GuzzleHttp\Client for making API requests.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Handle the request to fetch articles from the New York Times API.
     *
     * This method constructs a request to the New York Times API,
     * retrieves the articles, and returns them in an array format.
     *
     * @param mixed $request The initial request data (not used in this stage).
     * @return array An array of articles retrieved from the API.
     */
    public function handle($request)
    {
        $apiKey = env('NYT_NEWS_API_KEY');
        $url = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
        $queryParams = ['api-key' => $apiKey, 'sort' => 'newest'];

        $response = $this->client->get($url, ['query' => $queryParams]);
        $articles = json_decode($response->getBody()->getContents(), true);

        return $articles['response']['docs'] ?? [];
    }
}