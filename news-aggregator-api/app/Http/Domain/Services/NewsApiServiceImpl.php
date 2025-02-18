<?php
namespace App\Http\Domain\Services;

use App\Http\Constants\NewsSourceConstants;
use App\Http\Services\NewsApi;
use GuzzleHttp\Client;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Class NewsApiServiceImpl
 *
 * Implements the NewsApi to fetch articles from an external news API.
 * This class uses GuzzleHttp\Client to make HTTP requests and stores the articles
 * in the local database using the Article model.
 */
class NewsApiServiceImpl implements NewsApi
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * NewsApiServiceImpl constructor.
     *
     * Initializes the Guzzle HTTP client used for making API requests.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Fetch articles from the news API and save them to the database.
     *
     * Makes a request to the news API to retrieve the top headlines,
     * decodes the JSON response, and updates or creates Article records
     * in the database based on the fetched data.
     *
     * @return void
     */
    public function fetchArticles()
    {
        $apiKey = env('NEWS_API_KEY');
        $url = 'https://newsapi.org/v2/top-headlines?apiKey=' . $apiKey . '&country=us';
        $response = $this->client->get($url);
        $articles = json_decode($response->getBody()->getContents(), true);

        Log::info('Articles', $articles);

        foreach ($articles['articles'] as $article) {
            Article::updateOrCreate(
                ['title' => $article['title']],
                [
                    'content' => $article['content'],
                    'source' => NewsSourceConstants::NEWS_API,
                    'category' => $article['category'] ?? 'General',
                    'author' => $article['author'] ?? null,
                    'description' => $article['description'] ?? null,
                    'link' => $article['url'] ?? null,
                    'image' => $article['urlToImage'] ?? null,
                    'published_at' => $article['publishedAt'] ? Carbon::parse($article['publishedAt'])->toDateTimeString() : null,
                ]
            );
        }
    }
}