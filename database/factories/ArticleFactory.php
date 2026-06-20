<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);
        return [
            'title'        => $title,
            'slug'         => \Illuminate\Support\Str::slug($title),
            'category_id'  => Category::factory(),
            'writer'       => fake()->name(),
            'status'       => 'active',
            'content'      => fake()->paragraphs(3, true),
            'image'        => null,
            'views'        => fake()->numberBetween(0, 500),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'draft']);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'active']);
    }
}
