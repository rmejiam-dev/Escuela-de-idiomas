<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Procedure;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $isVerified = fake()->boolean(80);
        
        return [
            'procedure_id' => Procedure::factory(),
            'amount' => fake()->randomFloat(2, 50, 500),
            'payment_method' => fake()->randomElement(['credit_card', 'debit_card', 'bank_transfer', 'cash', 'paypal']),
            'reference_number' => fake()->unique()->numerify('PAY-##########'),
            'receipt_path' => fake()->optional(0.7)->filePath(),
            'is_verified' => $isVerified,
            'verified_at' => $isVerified ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'verified_by' => $isVerified ? User::inRandomOrder()->first() : null,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
            'verified_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'verified_by' => User::inRandomOrder()->first(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null,
        ]);
    }
}