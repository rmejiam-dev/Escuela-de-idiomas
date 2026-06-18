<?php

namespace Database\Factories;

use App\Models\DigitalSignature;
use App\Models\Procedure;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DigitalSignatureFactory extends Factory
{
    protected $model = DigitalSignature::class;

    public function definition(): array
    {
        return [
            'procedure_id' => Procedure::factory(),
            'user_id' => User::inRandomOrder()->first(),
            'signer_name' => fake()->name(),
            'signer_position' => fake()->randomElement(['Director', 'Secretary Academic', 'Dean', 'Coordinator']),
            'signature_image_path' => 'signatures/' . fake()->uuid() . '.png',
            'signature_hash' => hash('sha256', fake()->uuid()),
            'signed_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}