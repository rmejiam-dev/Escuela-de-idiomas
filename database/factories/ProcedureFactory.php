<?php

namespace Database\Factories;

use App\Models\Procedure;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcedureFactory extends Factory
{
    protected $model = Procedure::class;

    public function definition(): array
    {
        $statuses = [            
            Procedure::STATUS_SECRETARY,
            Procedure::STATUS_FINANCE,
            Procedure::STATUS_ACADEMIC_REVIEW,
            Procedure::STATUS_SIGNATURE,
            Procedure::STATUS_OBSERVATION,
            Procedure::STATUS_COMPLETED,
        ];

        $status = fake()->randomElement($statuses);
        $receivedAt = fake()->dateTimeBetween('-6 months', 'now');
        
        $secretaryApprovedAt = null;
        $academicReviewedAt = null;
        $financeAt = null;
        $signedAt = null;
        $completedAt = null;

        if ($status !== Procedure::STATUS_SECRETARY) {
            $secretaryApprovedAt = fake()->dateTimeBetween($receivedAt, 'now');
        }
        
        if (in_array($status, [Procedure::STATUS_SIGNATURE, Procedure::STATUS_COMPLETED])) {
            $academicReviewedAt = fake()->dateTimeBetween($receivedAt, 'now');
        }
        
        if ($status === Procedure::STATUS_COMPLETED) {
            $financeAt = fake()->dateTimeBetween($receivedAt, 'now');
            $signedAt = fake()->dateTimeBetween($receivedAt, 'now');
            $completedAt = fake()->dateTimeBetween($receivedAt, 'now');
        }

        return [
            'user_id' => User::inRandomOrder()->first(),
            'certificate_type' => fake()->randomElement(['academic_record', 'language_certificate', 'study_certificate']),
            'student_name' => fake()->name(),
            'student_identification' => fake()->numerify('##########'),
            'birth_date' => fake()->dateTimeBetween('-30 years', '-18 years'),
            'program' => fake()->randomElement(['english', 'french', 'german', 'portuguese', 'italian']),
            'study_period' => fake()->numberBetween(1, 12),
            'final_grades_average' => fake()->optional(0.7, null)->randomFloat(2, 3.0, 5.0),
            'status' => $status,
            'observations' => fake()->optional(0.3)->sentence(),
            'received_at' => $receivedAt,
            'secretary_approved_at' => $secretaryApprovedAt,
            'finance_approved_at' => $financeAt,
            'academic_reviewed_at' => $academicReviewedAt,
            'signed_at' => $signedAt,
            'completed_at' => $completedAt,
            'certificate_file_path' => fake()->optional(0.5)->filePath(),
        ];
    }

    public function reception(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Procedure::STATUS_SECRETARY,
            'secretary_approved_at' => null,
            'finance_approved_at' => null,
            'academic_reviewed_at' => null,
            'signed_at' => null,
            'completed_at' => null,
        ]);
    }

    public function secretary(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Procedure::STATUS_SECRETARY,
            'secretary_approved_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'finance_approved_at' => null,
            'academic_reviewed_at' => null,
            'signed_at' => null,
            'completed_at' => null,
        ]);
    }

    public function academicReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Procedure::STATUS_ACADEMIC_REVIEW,
            'secretary_approved_at' => fake()->dateTimeBetween('-5 months', '-2 months'),
            'finance_approved_at' => fake()->dateTimeBetween('-4 months', 'now'),
            'academic_reviewed_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'signed_at' => null,
            'completed_at' => null,
        ]);
    }

    public function signature(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Procedure::STATUS_SIGNATURE,
            'secretary_approved_at' => fake()->dateTimeBetween('-6 months', '-3 months'),
            'finance_approved_at' => fake()->dateTimeBetween('-5 months', '-4 months'),
            'academic_reviewed_at' => fake()->dateTimeBetween('-4 months', '-1 months'),
            'signed_at' => null,
            'completed_at' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Procedure::STATUS_COMPLETED,
            'secretary_approved_at' => fake()->dateTimeBetween('-6 months', '-4 months'),
            'finance_approved_at' => fake()->dateTimeBetween('-5 months', '-4 months'),
            'academic_reviewed_at' => fake()->dateTimeBetween('-3 months', '-2 months'),
            'signed_at' => fake()->dateTimeBetween('-4 months', '-1 months'),
            'completed_at' => fake()->dateTimeBetween('-1 months', 'now'),
        ]);
    }

    public function observation(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Procedure::STATUS_OBSERVATION,
            'observations' => fake()->sentence(),
            'secretary_approved_at' => null,
            'academic_reviewed_at' => null,
            'signed_at' => null,
            'completed_at' => null,
        ]);
    }
}