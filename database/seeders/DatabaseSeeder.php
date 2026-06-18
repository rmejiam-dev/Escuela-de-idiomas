<?php

namespace Database\Seeders;

use App\Models\DigitalSignature;
use App\Models\Payment;
use App\Models\PreEnrollment;
use App\Models\Procedure;
use App\Models\ProcedureHistory;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        User::factory(50)->create()->each(function ($user) {
            $role = Role::inRandomOrder()->first();
            $user->assignRole($role->name);
        });

        PreEnrollment::factory(100)->create();

        $statusFlow = [
            'reception',
            'secretary',
            'academic_review',
            'signature',
            'completed',
        ];

        for ($i = 0; $i < 200; $i++) {
            $procedure = Procedure::factory()->reception()->create();

            foreach ($statusFlow as $index => $status) {
                if ($index > 0 && fake()->boolean(70)) {
                    $procedure->updateStatus($status, User::inRandomOrder()->first()->id);
                    $procedure->refresh();
                }
            }

            if ($procedure->status === 'signature' || $procedure->status === 'completed') {
                DigitalSignature::factory()->create(['procedure_id' => $procedure->id]);

                if (fake()->boolean(60)) {
                    DigitalSignature::factory()->create(['procedure_id' => $procedure->id]);
                }
            }

            if (fake()->boolean(80)) {
                Payment::factory()->create(['procedure_id' => $procedure->id]);
            }

            ProcedureHistory::factory(rand(2, 5))->create(['procedure_id' => $procedure->id]);
        }

        Procedure::factory(50)->observation()->create();
        Payment::factory(30)->pending()->create();
    }
}
