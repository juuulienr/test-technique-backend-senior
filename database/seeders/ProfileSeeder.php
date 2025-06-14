<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::factory()->count(20)->create();
    }
}
