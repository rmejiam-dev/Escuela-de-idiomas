<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'manage procedures']);
        Permission::create(['name' => 'create procedures']);
        Permission::create(['name' => 'sign procedures']);
        Permission::create(['name' => 'manage payments']);
        Permission::create(['name' => 'verify payments']);
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'manage pre_enrollments']);
        Permission::create(['name' => 'view pre_enrollments']);

        Permission::create(['name' => 'view all procedures']);
        Permission::create(['name' => 'view own procedures']);

        Permission::create(['name' => 'review procedures']);
        Permission::create(['name' => 'review own procedures']);

        Permission::create(['name' => 'edit procedures']);
        Permission::create(['name' => 'edit own procedures']);
        
        
        Permission::create(['name' => 'review academic']);


        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $secretaryRole = Role::create(['name' => 'secretary']);
        $secretaryRole->givePermissionTo([
            'view dashboard',
            'view all procedures',
            'review procedures',
            'create users',
            'edit users',
            'view reports',
            'create procedures'
        ]);
        $accountantRole = Role::create(['name' => 'accountant']);
        $accountantRole->givePermissionTo([
            'view dashboard',
            'view all procedures',
            'verify payments',
            'manage payments',
            'view reports',
            'create procedures'
        ]);

        $academicRole = Role::create(['name' => 'academic']);
        $academicRole->givePermissionTo([
            'view dashboard',
            'view all procedures',
            'review academic',
            'view reports',
            'create procedures'
        ]);

        $signerRole = Role::create(['name' => 'signer']);
        $signerRole->givePermissionTo([
            'view dashboard',
            'view all procedures',
            'sign procedures',
            'view reports',
            'create procedures'
        ]);

        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view dashboard',
            'create procedures',
            'view own procedures',
            'review own procedures',
            'edit own procedures'
        ]);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'rmejiam.dev@gmail.com',
            'password' => bcrypt('12345678'),
            'identification_number' => 'ADMIN001',
            'phone' => '000000000',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $secretary = User::create([
            'name' => 'Secretary',
            'email' => 'secretary@escuela.com',
            'password' => bcrypt('12345678'),
            'identification_number' => 'SEC001',
            'phone' => '000000000',
            'is_active' => true,
        ]);
        $secretary->assignRole('secretary');

        $academic = User::create([
            'name' => 'Academic',
            'email' => 'academic@escuela.com',
            'password' => bcrypt('12345678'),
            'identification_number' => 'ACA001',
            'phone' => '000000000',
            'is_active' => true,
        ]);
        $academic->assignRole('academic');

        $signer = User::create([
            'name' => 'Signer',
            'email' => 'signer@escuela.com',
            'password' => bcrypt('12345678'),
            'identification_number' => 'SIG001',
            'phone' => '000000000',
            'is_active' => true,
        ]);
        $signer->assignRole('signer');
        $student = User::create([
            'name' => 'Student',
            'email' => 'student@escuela.com',
            'password' => bcrypt('12345678'),
            'identification_number' => 'STU001',
            'phone' => '000000000',
            'is_active' => true,
        ]);
        $student->assignRole('student');
        $accountant = User::create([
            'name' => 'Accountant',
            'email' => 'accountant@escuela.com',
            'password' => bcrypt('12345678'),
            'identification_number' => 'ACC001',
            'phone' => '000000000',
            'is_active' => true,
        ]);
        $accountant->assignRole('accountant');
    }
}
