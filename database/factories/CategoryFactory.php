<?php

namespace CafeHistoire\CafeBlog\Database\Factories;

use CafeHistoire\CafeBlog\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $icons = ['☕', '🌍', '🔥', '📜', '🏛️', '🧪', '📚', '🍵'];

        return [
            'name'        => $this->faker->unique()->words(rand(2, 4), true),
            'description' => $this->faker->sentence(12),
            'color'       => $this->faker->randomElement(['#6B3A2A', '#2D5A27', '#8B4513', '#5C4A7A', '#4A2C2A']),
            'icon'        => $this->faker->randomElement($icons),
            'sort_order'  => $this->faker->numberBetween(0, 20),
            'is_active'   => true,
        ];
    }
}
