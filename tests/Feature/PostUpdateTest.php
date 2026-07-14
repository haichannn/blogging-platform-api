<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PostUpdateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[TestDox('It should update a post successfully with valid data')]
    public function it_updates_post_successfully_with_valid_data(): void
    {
        // 1. Create a post to be updated.
        $post = Post::factory()->create();

        // 2. Define the new data for the update.
        $updateData = [
            'title' => 'My Updated Post Title',
            'content' => 'This is the new, updated content for the post.',
            'category' => 'Updated Category',
            'tags' => ['Updated', 'PHP'],
        ];

        // 3. Call the PATCH endpoint.
        $response = $this->patchJson('/api/posts/' . $post->id, $updateData);

        // 4. Assert the response is 200 OK and contains the updated data.
        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $post->id,
                'title' => 'My Updated Post Title',
                'category' => 'Updated Category',
            ]);

        // 5. Assert the database has been updated with the new data.
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'My Updated Post Title',
        ]);
    }

    #[Test]
    #[TestDox('It should fail with a validation error if the title is sent but empty')]
    public function it_fails_validation_if_title_is_empty(): void
    {
        $post = Post::factory()->create();

        // Data is sent with an empty 'title', which should fail validation.
        $updateData = [
            'title' => '',
            'content' => 'Some new content.',
        ];

        $response = $this->patchJson('/api/posts/' . $post->id, $updateData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    #[Test]
    #[TestDox('It should return a 404 Not Found error if the post to be updated does not exist')]
    public function it_returns404_if_post_to_update_does_not_exist(): void
    {
        $nonExistentId = 999;
        $updateData = ['title' => 'A title']; // A valid payload is needed.

        $response = $this->patchJson('/api/posts/' . $nonExistentId, $updateData);

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Post not found.',
            ]);
    }

    #[Test]
    #[TestDox('It should allow partial updates (e.g., only updating the title)')]
    public function it_allows_partial_updates(): void
    {
        // Note: This test assumes the validation rules in the FormRequest
        // are adjusted for PATCH (e.g., using 'sometimes' or being nullable).

        $post = Post::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original Content',
        ]);

        $updateData = [
            'title' => 'Just An Updated Title',
        ];

        $response = $this->patchJson('/api/posts/' . $post->id, $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Just An Updated Title',      // Title should be updated.
            'content' => 'Original Content', // Content should remain unchanged.
        ]);
    }

    #[Test]
    #[TestDox('It should fail with a validation error if the content is sent but empty')]
    public function it_fails_validation_if_content_is_empty(): void
    {
        $post = Post::factory()->create();

        // Data is sent with an empty 'content', which should fail validation.
        $updateData = [
            'content' => '',
        ];

        $response = $this->patchJson('/api/posts/' . $post->id, $updateData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    #[Test]
    #[TestDox('It should fail with a validation error if the category is sent but empty')]
    public function it_fails_validation_if_category_is_empty(): void
    {
        $post = Post::factory()->create();

        // Data is sent with an empty 'category', which should fail validation.
        $updateData = [
            'category' => '',
        ];

        $response = $this->patchJson('/api/posts/' . $post->id, $updateData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('category');
    }
}
