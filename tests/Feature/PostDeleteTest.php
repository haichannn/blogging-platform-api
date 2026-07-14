<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PostDeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[TestDox('It should delete a post successfully and return a 204 No Content response')]
    public function it_deletes_post_successfully(): void
    {
        // 1. Create a post to be deleted.
        $post = Post::factory()->create();

        // 2. Assert the post exists in the database before deletion.
        $this->assertDatabaseHas('posts', ['id' => $post->id]);

        // 3. Call the DELETE endpoint.
        $response = $this->deleteJson('/api/posts/' . $post->id);

        // 4. Assert the response is 204 No Content and has an empty body.
        $response->assertStatus(204);
        $this->assertEmpty($response->getContent());

        // 5. Assert the post is missing from the database after deletion.
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    #[Test]
    #[TestDox('It should return a 404 Not Found error if the post to be deleted does not exist')]
    public function it_returns404_if_post_to_be_deleted_does_not_exist(): void
    {
        $nonExistentId = 999;

        // Call the DELETE endpoint with a non-existent ID.
        $response = $this->deleteJson('/api/posts/' . $nonExistentId);

        // Assert the response is 404 with the correct error format.
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Post not found.',
            ]);
    }

    #[Test]
    #[TestDox('It should also return a 404 Not Found for non-numeric IDs when attempting to delete')]
    public function it_returns404_for_non_numeric_ids_on_delete_attempt(): void
    {
        $invalidId = 'abc';

        // Call the DELETE endpoint with a non-numeric ID.
        $response = $this->deleteJson('/api/posts/' . $invalidId);

        // Assert the missing() method works and returns a 404 response.
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Post not found.',
            ]);
    }
}
