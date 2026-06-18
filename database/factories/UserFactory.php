<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'identification_number' => fake()->unique()->numerify('##########'),
            'is_active' => fake()->boolean(90),
        ];
    }

    // Método para asignar un rol específico
    public function withRole(string $roleName): static
    {
        return $this->afterCreating(function (User $user) use ($roleName) {
            $user->assignRole($roleName);
        });
    }

    // Método para asignar múltiples roles
    public function withRoles(array $roleNames): static
    {
        return $this->afterCreating(function (User $user) use ($roleNames) {
            $user->assignRole($roleNames);
        });
    }

    // Método para asignar un rol aleatorio
    public function withRandomRole(): static
    {
        return $this->afterCreating(function (User $user) {
            $roles = Role::pluck('name')->toArray();
            if (!empty($roles)) {
                $randomRole = fake()->randomElement($roles);
                $user->assignRole($randomRole);
            }
        });
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}