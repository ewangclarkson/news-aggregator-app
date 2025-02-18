<?php

namespace Database\Factories;

use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsSourceFactory extends Factory
{
    protected $model = NewsSource::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company, // or any relevant data type
            'service_class' => $this->faker->word, // Adjust as needed
        ];
    }
}