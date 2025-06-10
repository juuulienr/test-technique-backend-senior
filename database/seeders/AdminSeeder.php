<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::factory()->count(10)->create();
    }
}
