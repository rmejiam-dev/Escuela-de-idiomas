<?php

namespace Database\Factories;

use App\Models\PreEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreEnrollmentFactory extends Factory
{
    protected $model = PreEnrollment::class;

    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'identification_number' => fake()->unique()->numerify('##########'),
            'program_interest' => fake()->randomElement(['english', 'french', 'german', 'portuguese', 'italian', 'mandarin']),
            'message' => fake()->optional(0.6)->paragraph(),
            'status' => fake()->randomElement(['pending', 'contacted', 'enrolled', 'rejected']),
            'request_ip' => fake()->ipv4(),
            'captcha_response' => fake()->optional(0.9)->word(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function contacted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'contacted',
        ]);
    }

    public function enrolled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'enrolled',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}