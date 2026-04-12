<?php

namespace CafeHistoire\CafeBlog\Database\Factories;

use CafeHistoire\CafeBlog\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'bio'               => $this->faker->paragraph(2),
            'coffee_preference' => $this->faker->randomElement([
                'Éthiopien naturel, torréfaction légère',
                'Espresso yéménite Moka',
                'Washed Kenyan AA',
                'Colombian single origin',
                'Brazilian natural, medium roast',
            ]),
            'origin_specialty'  => $this->faker->randomElement(array_keys(\CafeHistoire\CafeBlog\Models\Post::ORIGINS)),
            'is_active'         => true,
        ];
    }
}
