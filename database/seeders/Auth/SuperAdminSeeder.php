<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{

    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'uuid'           => (string) Str::uuid(),
                'first_name'     => 'Super',
                'middle_name'    => 'System',
                'last_name'      => 'Admin',
                'contact_number' => '09271852710',
                'password'       => Hash::make('password'), 
            ]
        );
        
        if (!$user->hasRole('superadmin')) {
            $user->assignRole('superadmin');
        }

        for ($i = 1; $i <= 5; $i++) {
            $email = "judge{$i}@gmail.com";

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'uuid'           => (string) Str::uuid(),
                    'first_name'     => 'Judge',
                    'middle_name'    => 'No',
                    'last_name'      => (string) $i,
                    'contact_number' => '09' . rand(100000000, 999999999),
                    'password'       => Hash::make('password'),
                ]
            );

            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
            }
        }
    }
}
