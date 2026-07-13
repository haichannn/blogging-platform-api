<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PostCreateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[TestDox('It should create a post successfully with valid data')]
    public function it_creates_post_successfully_with_valid_data(): void
    {
        $postData = [
            'title' => 'My First Real Post',
            'content' => 'This content adheres to the product requirements document.',
            'category' => 'Technology',
            'tags' => ['Laravel', 'TDD', 'PHPUnit'],
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'category',
                'tags',
                'createdAt',
                'updatedAt',
            ])
            ->assertJson([
                'title' => 'My First Real Post',
                'category' => 'Technology',
                'tags' => ['Laravel', 'TDD', 'PHPUnit'],
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'My First Real Post',
        ]);
    }

    #[Test]
    #[TestDox('It should create a post successfully when tags are null')]
    public function it_creates_post_successfully_when_tags_are_null(): void
    {
        $postData = [
            'title' => 'Post with Null Tags',
            'content' => 'This post is created with a null value for tags.',
            'category' => 'General',
            'tags' => null,
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(201)
            ->assertJson([
                'title' => 'Post with Null Tags',
                'tags' => null
            ]);

        $this->assertDatabaseHas('posts', ['title' => 'Post with Null Tags']);
    }

    #[Test]
    #[TestDox('It should fail with a validation error if title is missing')]
    public function it_fails_with_validation_error_if_title_is_missing(): void
    {
        $postData = [
            // title is missing
            'content' => 'This post has no title.',
            'category' => 'Errors',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    #[Test]
    #[TestDox('It should fail with a validation error if content is missing')]
    public function it_fails_with_validation_error_if_content_is_missing(): void
    {
        $postData = [
            'title' => 'Title without content',
            // content is missing
            'category' => 'Errors',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    #[Test]
    #[TestDox('It should fail with a validation error if category is missing')]
    public function it_fails_with_validation_error_if_category_is_missing(): void
    {
        $postData = [
            'title' => 'Post without a category',
            'content' => 'This post is missing its category.',
            // category is missing
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['category']);
    }

    #[Test]
    #[TestDox('It should fail with a validation error if the title is longer than 255 characters')]
    public function it_fails_if_title_exceeds_max_length(): void
    {
        $postData = [
            'title' => str_repeat('A', 256), // Exactly 256 characters
            'content' => 'Valid content.',
            'category' => 'Valid Category',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    #[Test]
    #[TestDox('It should fail with a validation error if the category is longer than 100 characters')]
    public function it_fails_if_category_exceeds_max_length(): void
    {
        $postData = [
            'title' => 'Valid Title',
            'content' => 'Valid content.',
            'category' => str_repeat('B', 101), // Exactly 101 characters
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('category');
    }

    #[Test]
    #[TestDox('It should fail with a validation error if tags are not an array')]
    public function it_fails_if_tags_are_not_an_array(): void
    {
        $postData = [
            'title' => 'Valid Title',
            'content' => 'Valid content.',
            'category' => 'Valid Category',
            'tags' => 'this-is-a-string-not-an-array', // Invalid type
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('tags');
    }

    #[Test]
    #[TestDox('It should fail with a validation error if the title is an empty string')]
    public function it_fails_if_title_is_an_empty_string(): void
    {
        $postData = [
            'title' => '', // Empty string, should be caught by 'required'
            'content' => 'Content for a post with an empty title.',
            'category' => 'Valid Category',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    #[Test]
    #[TestDox('It should fail with a validation error if an item in tags is not a string')]
    public function it_fails_if_an_item_in_tags_is_not_a_string(): void
    {
        $postData = [
            'title' => 'Valid Title',
            'content' => 'Valid content.',
            'category' => 'Valid Category',
            'tags' => ['PHP', 123, 'Laravel'], // '123' is an integer, not a string
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('tags.1'); // Laravel should identify the specific invalid item
    }
}
