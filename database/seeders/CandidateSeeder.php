<?php

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 33; $i++) {
            Participant::firstOrCreate([
                'name'    => 'Candidate ' . $i,
            ]);
        }
    }
}
