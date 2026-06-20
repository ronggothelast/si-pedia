<?php

namespace Database\Factories;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

class LecturerFactory extends Factory
{
    protected $model = Lecturer::class;

    public function definition(): array
    {
        return [
            'full_name'       => fake()->name(),
            'nidn'            => fake()->unique()->numerify('#########'),
            'nip'             => fake()->numerify('##############'),
            'username'        => fake()->unique()->userName(),
            'email'           => fake()->unique()->safeEmail(),
            'phone'           => fake()->phoneNumber(),
            'address'         => fake()->city(),
            'place_of_birth'  => fake()->city(),
            'date_of_birth'   => fake()->date(),
            'gender'          => fake()->randomElement(['Male', 'Female']),
            'study_program'   => fake()->randomElement(['Sistem Informasi', 'Teknik Informatika']),
            'expertise'       => fake()->randomElement(['Programming', 'Database', 'Networking', 'AI']),
            'photo'           => null,
            'status'          => 'active',
        ];
    }

    public function waiting(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'waiting']);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'active']);
    }
}
