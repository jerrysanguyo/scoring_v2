<?php

namespace Database\Seeders;

use Database\Seeders\Auth\SuperAdminSeeder;
use Database\Seeders\Cms\RoleAndPermissionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            SuperAdminSeeder::class,
            CandidateSeeder::class,
        ]);
    }
}
