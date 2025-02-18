<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'category' => $this->faker->word,
            'source' => $this->faker->word,
            'content' => $this->faker->paragraph,
            'author' => $this->faker->name,
            'description' => $this->faker->paragraph(2),
            'link' => $this->faker->url,
            'image' => $this->faker->imageUrl(),
            'published_at' => $this->faker->dateTimeBetween('-1 month')
        ];
    }
}