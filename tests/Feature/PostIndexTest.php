<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PostIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[TestDox('It should return an empty array when no posts exist')]
    public function it_returns_empty_array_when_no_posts_exist(): void
    {
        $response = $this->getJson('/api/posts');

        $response
            ->assertStatus(200)
            ->assertJsonCount(0) // Memastikan array JSON yang dikembalikan kosong
            ->assertExactJson([]); // Memastikan responsnya adalah array kosong []
    }

    #[Test]
    #[TestDox('It should return a list of all posts')]
    public function it_returns_a_list_of_all_posts(): void
    {
        // Membuat 3 postingan untuk pengujian
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3) // Memastikan ada 3 item dalam array JSON
            ->assertJsonStructure([ // Memeriksa struktur dari setiap item dalam array
                '*' => [
                    'id',
                    'title',
                    'content',
                    'category',
                    'tags',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }
}
