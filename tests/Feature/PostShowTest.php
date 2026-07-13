<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PostShowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[TestDox('It should return a single post successfully when the post exists')]
    public function it_returns_single_post_successfully_when_post_exists(): void
    {
        // 1. Create a post to be retrieved.
        $post = Post::factory()->create();

        // 2. Call the endpoint to retrieve that post.
        $response = $this->getJson('/api/posts/' . $post->id);

        // 3. Assert the response is correct.
        $response
            ->assertStatus(200)
            ->assertJsonStructure([ // Check the JSON structure matches the PRD.
                'id',
                'title',
                'content',
                'category',
                'tags',
                'createdAt',
                'updatedAt',
            ])
            ->assertJson([ // Assert the returned data matches the created post.
                'id' => $post->id,
                'title' => $post->title,
            ]);
    }

    #[Test]
    #[TestDox('It should return a 404 Not Found error if the post does not exist')]
    public function it_returns404_not_found_if_post_does_not_exist(): void
    {
        $nonExistentId = 999;

        // Call the endpoint with an ID that doesn't exist in the database.
        $response = $this->getJson('/api/posts/' . $nonExistentId);

        // Assert the response is 404 with the correct error format.
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'errors' => [
                    'message' => 'Post not found.',
                ],
            ]);
    }

    #[Test]
    #[TestDox('It should also return a 404 Not Found for non-numeric IDs')]
    public function it_returns404_not_found_for_non_numeric_ids(): void
    {
        $invalidId = 'abc'; // An ID with an invalid format (non-numeric).

        // Call the endpoint with a non-numeric ID.
        $response = $this->getJson('/api/posts/' . $invalidId);

        // Assert that the routing's missing() method works and returns a 404
        // with the same error format as a non-existent ID.
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'errors' => [
                    'message' => 'Post not found.',
                ],
            ]);
    }
}
