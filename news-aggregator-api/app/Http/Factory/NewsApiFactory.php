<?php
namespace App\Http\Factory;

namespace App\Http\Factory;


use App\Http\Domain\Services\GuardianNewsServiceImpl;
use App\Http\Domain\Services\NewsApiServiceImpl;
use App\Http\Domain\Services\NytNewsServiceImpl;
use App\Http\Services\NewsApi;

/**
 * Class NewsApiFactory
 *
 * Factory class responsible for creating instances of different
 * news API implementations that conform to the NewsApi.
 * This class provides a simple way to instantiate the correct
 * implementation based on a given type.
 */
class NewsApiFactory
{
    /**
     * Create an instance of a news API implementation.
     *
     * @param string $type The type of news API implementation to create.
     *                     Valid options are 'news_api', 'open_news',
     *                     and 'news_cred'.
     *
     * @return NewsApi An instance of a class that implements
     *                          the NewsApi.
     *
     * @throws \Exception If an invalid type is provided.
     */
    public function create(string $type): NewsApi
    {
        switch ($type) {
            case 'news_api':
                return app(NewsApiServiceImpl::class);
            case 'guardian_news':
                return app(GuardianNewsServiceImpl::class);
            case 'nyt_news':
                return app(NytNewsServiceImpl::class);
            default:
                throw new \Exception("Invalid news API type: {$type}");
        }
    }
}