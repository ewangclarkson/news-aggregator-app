<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'preferences' => [
                'sources' => $this->faker->words(3, true), // Array of sources
                'categories' => $this->faker->words(2, true), // Array of categories
                'authors' => $this->faker->words(2, true), // Array of authors
            ],
        ];
    }
}