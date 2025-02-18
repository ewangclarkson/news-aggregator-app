<?php
namespace App\Http\Domain\Services\pipeline;

use App\Http\Constants\NewsSourceConstants;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Class NytProcessArticlesStage
 *
 * This class represents a stage in the article processing pipeline
 * responsible for updating or creating Article records in the
 * database based on the articles retrieved from the New York Times API.
 *
 * @package App\Http\Services\Implementation\pipeline
 */
class NytProcessArticlesStage
{
    /**
     * Handle the processing of articles.
     *
     * This method iterates through the provided articles and updates
     * or creates Article records in the local database. It extracts
     * relevant fields from each article and logs the success of the
     * operation.
     *
     * @param array $articles An array of articles fetched from the API.
     * @return void
     */
    public function handle($articles)
    {
        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['title' => $article['headline']['main']],
                [
                    'content' => $article['lead_paragraph'] ?? null,
                    'source' => NewsSourceConstants::NEW_YORK_TIME_NEWS_API,
                    'category' => $article['section_name'] ?? 'General',
                    'author' => $this->getAuthors($article['byline'] ?? []),
                    'description' => $article['abstract'] ?? null,
                    'link' => $article['web_url'] ?? null,
                    'image' => $article['multimedia'][0]['url'] ?? null,
                    'published_at' => $article['pub_date'] ? Carbon::parse($article['pub_date'])->toDateTimeString() : null,
                ]
            );
        }

        Log::info('Articles processed successfully.');
    }

    /**
     * Helper method to get authors from the byline.
     *
     * This method extracts author names from the byline information.
     * It first checks for the 'original' key and, if not found,
     * falls back to the 'person' key to retrieve the author's name.
     *
     * @param array $byline The byline information from the article.
     * @return string|null Returns a comma-separated list of authors or null if not found.
     */
    private function getAuthors(array $byline): ?string
    {
        // Check if 'original' exists and is an array
        if (isset($byline['original']) && is_array($byline['original'])) {
            return implode(', ', $byline['original']);
        }

        // Check if 'person' exists and has at least one entry
        if (isset($byline['person']) && is_array($byline['person']) && count($byline['person']) > 0) {
            $firstPerson = $byline['person'][0];
            $firstname = $firstPerson['firstname'] ?? '';
            $lastname = $firstPerson['lastname'] ?? '';

            return trim("$firstname $lastname") ?: 'Unknown Author';
        }

        // Fallback if no author information is available
        return null;
    }
}