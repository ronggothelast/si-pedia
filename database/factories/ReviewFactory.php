<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(3),
            'type'        => fake()->randomElement(['Social media', 'Website', 'Blog']),
            'description' => fake()->paragraph(),
            'image'       => null,
            'avatar'      => null,
            'views'       => fake()->numberBetween(0, 2000),
            'status'      => 'pending',
            'reviewed_at' => fake()->date(),
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'accepted']);
    }

    public function declined(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'declined']);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'pending']);
    }
}
