<?php

namespace Database\Seeders;

use App\Http\Constants\NewsSourceConstants;
use Illuminate\Database\Seeder;

use App\Models\NewsSource;
class NewsSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewsSource::create([
            'name' => NewsSourceConstants::NEWS_API,
            'service_class' => 'App/Http/Domain/Services/NewsApiServiceImpl',
        ]);

        NewsSource::create([
            'name' => NewsSourceConstants::GUARDIAN_NEWS_API,
            'service_class' => 'App/Http/Domain/Services/GuardianNewsServiceImpl',
        ]);

        NewsSource::create([
            'name' => NewsSourceConstants::NEW_YORK_TIME_NEWS_API,
            'service_class' => 'App/Http/Domain/Services/NytNewsServiceImpl',
        ]);
    }
}
