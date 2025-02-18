<?php

namespace App\Console\Commands;

use App\Http\Domain\Services\NewsServiceStrategy;
use App\Http\Factory\NewsApiFactory;
use Illuminate\Console\Command;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from news APIs';

    /**
     * @var NewsServiceStrategy
     */
    protected $newsServiceStrategy;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $newsApiFactory;

    public function __construct(NewsApiFactory $newsApiFactory)
    {
        parent::__construct();
        $this->newsApiFactory = $newsApiFactory;
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $lastRunFile = storage_path('app/last_fetch_articles_run.txt');

        if (file_exists($lastRunFile)) {
            $lastRun = file_get_contents($lastRunFile);
        } else {
            $lastRun = null;
        }
        $currentTime = time();
        if ($lastRun === null || ($currentTime - $lastRun) >= 3600) {
            $apiTypes = ['news_api', 'guardian_news', 'nyt_news'];

            foreach ($apiTypes as $type) {
                $newsApi = $this->newsApiFactory->create($type);
                $newsServiceStrategy = new NewsServiceStrategy($newsApi);
                $newsServiceStrategy->fetchArticles();
            }

            file_put_contents($lastRunFile, $currentTime);

            $this->info('Articles fetched successfully!');
        } else {
            $this->info('Fetch command already executed within the last hour. Skipping...');
        }
    }
}
