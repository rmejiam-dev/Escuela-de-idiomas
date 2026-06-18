<?php

namespace Database\Factories;

use App\Models\Procedure;
use App\Models\ProcedureHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcedureHistoryFactory extends Factory
{
    protected $model = ProcedureHistory::class;

    public function definition(): array
    {
        $statuses = [
            'reception',
            'secretary',
            'academic_review',
            'signature',
            'observation',
            'completed',
        ];

        return [
            'procedure_id' => Procedure::factory(),
            'user_id' => User::inRandomOrder()->first(),
            'action' => fake()->randomElement(['status_change', 'comment', 'document_upload', 'payment_verification']),
            'from_status' => fake()->randomElement($statuses),
            'to_status' => fake()->randomElement($statuses),
            'comments' => fake()->optional(0.7)->sentence(),
            'metadata' => fake()->optional(0.5)->passthrough(null),
        ];
    }

    public function statusChange(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'status_change',
            'metadata' => ['reason' => fake()->sentence()],
        ]);
    }

    public function comment(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'comment',
            'from_status' => null,
            'to_status' => null,
            'comments' => fake()->paragraph(),
        ]);
    }
}