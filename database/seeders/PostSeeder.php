<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the posts table with dummy data using the PostFactory.
     */
    public function run(): void
    {
        Post::factory(10)->create();
    }
}
