<?php
namespace App\Http\Domain\Services;

use App\Http\Constants\NewsSourceConstants;
use App\Http\Services\NewsApi;
use GuzzleHttp\Client;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Class GuardianNewsServiceImpl
 *
 * Implements the NewsApi to fetch articles from the OpenNews API.
 * This class uses GuzzleHttp\Client to make HTTP requests and updates
 * the articles in the local database using the Article model.
 */
class GuardianNewsServiceImpl implements NewsApi
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * GuardianNewsServiceImpl constructor.
     *
     * Initializes the Guzzle HTTP client used for making API requests.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Fetch articles from the OpenNews API and save them to the database.
     *
     * Makes a request to the OpenNews API to retrieve articles, decodes the
     * JSON response, and updates or creates Article records in the database
     * based on the fetched data.
     *
     * @return void
     */
    public function fetchArticles()
    {
        $apiKey = env('GAURDIAN_NEWS_API_KEY');
        $url = $url = 'https://content.guardianapis.com/search?api-key=' . $apiKey;
        $response = $this->client->get($url);
        $articles = json_decode($response->getBody()->getContents(), true);
        Log::info('Articles', $articles);
        foreach ($articles['response']['results'] as $article) {
            Article::updateOrCreate(
                ['title' => $article['webTitle']],
                [
                    'content' => $article['fields']['body'] ?? null,
                    'source' => NewsSourceConstants::GUARDIAN_NEWS_API,
                    'category' => $article['sectionName'] ?? 'General',
                    'author' => $article['fields']['byline'] ?? null,
                    'description' => $article['fields']['trailText'] ?? null,
                    'link' => $article['webUrl'] ?? null,
                    'image' => $article['fields']['thumbnail'] ?? null,
                    'published_at' => $article['webPublicationDate'] ? Carbon::parse($article['webPublicationDate'])->toDateTimeString() : null
                ]
            );
        }
    }
}