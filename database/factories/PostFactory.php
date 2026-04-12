<?php

namespace CafeHistoire\CafeBlog\Database\Factories;

use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Author;
use CafeHistoire\CafeBlog\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $status      = $this->faker->randomElement(['published', 'draft']);
        $publishedAt = $status === 'published' ? $this->faker->dateTimeBetween('-2 years', 'now') : null;

        return [
            'title'          => $this->faker->sentence(rand(5, 10)),
            'excerpt'        => $this->faker->paragraph(2),
            'content'        => $this->generateContent(),
            'author_id'      => Author::factory(),
            'category_id'    => Category::factory(),
            'status'         => $status,
            'is_featured'    => $this->faker->boolean(15),
            'coffee_origin'  => $this->faker->randomElement(array_keys(Post::ORIGINS)),
            'coffee_variety' => $this->faker->randomElement(['Arabica', 'Robusta', 'Typica', 'Bourbon', 'Gesha', 'Catuai']),
            'coffee_era'     => $this->faker->randomElement(array_keys(Post::ERAS)),
            'brewing_method' => $this->faker->randomElement(['Espresso', 'V60', 'Chemex', 'Aeropress', 'French Press', 'Cézve', 'Cold Brew']),
            'reading_time_minutes' => rand(3, 15),
            'views_count'    => rand(0, 10000),
            'published_at'   => $publishedAt,
        ];
    }

    public function published(): static
    {
        return $this->state([
            'status'       => Post::STATUS_PUBLISHED,
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function draft(): static
    {
        return $this->state([
            'status'       => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    private function generateContent(): string
    {
        $paragraphs = $this->faker->paragraphs(rand(4, 8));
        $html       = '';

        foreach ($paragraphs as $i => $p) {
            if ($i === 1) {
                $html .= '<h2>' . $this->faker->sentence(rand(3, 7)) . '</h2>' . PHP_EOL;
            }
            if ($i === 3) {
                $html .= '<blockquote><p>' . $this->faker->sentence(12) . '</p></blockquote>' . PHP_EOL;
            }
            $html .= '<p>' . $p . '</p>' . PHP_EOL;
        }

        return $html;
    }
}
