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
            ->assertJsonCount(0) // Assert that the returned JSON array is empty.
            ->assertExactJson([]); // Assert that the response is an empty array [].
    }

    #[Test]
    #[TestDox('It should return a list of all posts')]
    public function it_returns_a_list_of_all_posts(): void
    {
        // Create 3 posts for testing.
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3) // Assert there are 3 items in the JSON array.
            ->assertJsonStructure([ // Check the structure of each item in the array.
                '*' => [
                    'id',
                    'title',
                    'content',
                    'category',
                    'tags',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }
}
