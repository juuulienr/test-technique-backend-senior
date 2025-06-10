<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        Comment::factory(15)->create();
    }
}
