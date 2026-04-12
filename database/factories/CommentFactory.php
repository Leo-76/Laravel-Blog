<?php

namespace CafeHistoire\CafeBlog\Database\Factories;

use CafeHistoire\CafeBlog\Models\Comment;
use CafeHistoire\CafeBlog\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'post_id'      => Post::factory()->published(),
            'parent_id'    => null,
            'author_name'  => $this->faker->name(),
            'author_email' => $this->faker->safeEmail(),
            'content'      => $this->faker->paragraph(rand(1, 4)),
            'status'       => Comment::STATUS_APPROVED,
            'ip_address'   => $this->faker->ipv4(),
            'user_agent'   => $this->faker->userAgent(),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => Comment::STATUS_PENDING]);
    }

    public function spam(): static
    {
        return $this->state(['status' => Comment::STATUS_SPAM]);
    }
}
