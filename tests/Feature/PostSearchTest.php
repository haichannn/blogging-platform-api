<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PostSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment by creating a diverse set of posts.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a diverse set of posts to test against.
        Post::factory()->create(['title' => 'My First Post about Laravel', 'category' => 'PHP']);
        Post::factory()->create(['title' => 'A Guide to React', 'category' => 'JavaScript']);
        Post::factory()->create(['title' => 'Advanced Laravel Techniques', 'category' => 'PHP']);
        Post::factory()->create(['title' => 'Learning Vue.js', 'category' => 'JavaScript']);
    }

    #[Test]
    #[TestDox('It should return an empty array if no posts match the search term')]
    public function it_returns_empty_array_for_no_matching_posts(): void
    {
        $response = $this->getJson('/api/posts?search=NonExistentTerm');

        $response
            ->assertStatus(200)
            ->assertJsonCount(0)
            ->assertExactJson([]);
    }

    #[Test]
    #[TestDox('It should return all posts when the search term is empty')]
    public function it_returns_all_posts_when_search_term_is_empty(): void
    {
        // According to PRD, an empty search should probably return all posts.
        $response = $this->getJson('/api/posts?search=');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4); // Should return all 4 posts created in setUp().
    }

    #[Test]
    #[TestDox('It should filter posts by a search term in the title')]
    public function it_filters_posts_by_title(): void
    {
        $response = $this->getJson('/api/posts?search=Laravel');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2) // 'My First Post about Laravel' and 'Advanced Laravel Techniques'
            ->assertJsonFragment(['title' => 'My First Post about Laravel'])
            ->assertJsonFragment(['title' => 'Advanced Laravel Techniques']);
    }

    #[Test]
    #[TestDox('It should filter posts by a search term in the category')]
    public function it_filters_posts_by_category(): void
    {
        $response = $this->getJson('/api/posts?search=JavaScript');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2) // 'A Guide to React' and 'Learning Vue.js'
            ->assertJsonFragment(['category' => 'JavaScript']);
    }

    #[Test]
    #[TestDox('It should be case-insensitive in its search')]
    public function it_is_case_insensitive(): void
    {
        $response = $this->getJson('/api/posts?search=javascript'); // Lowercase search term

        $response
            ->assertStatus(200)
            ->assertJsonCount(2); // Should still find the 2 'JavaScript' category posts.
    }

    #[Test]
    #[TestDox('It should not return results for unrelated partial matches')]
    public function it_avoids_returning_unrelated_partial_matches(): void
    {
        // We clear the DB and create specific posts for this test
        // to avoid interference from data created in setUp().
        Post::query()->delete();

        $postA = Post::factory()->create(['title' => 'A post about a fast car']);
        $postB = Post::factory()->create(['title' => 'A post about casting a spell']);

        // A search for "cast" should only find "casting", not "fast".
        $response = $this->getJson('/api/posts?search=cast');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1) // Should only find one post.
            ->assertJsonFragment(['title' => $postB->title]) // Should contain Post B.
            ->assertJsonMissing(['title' => $postA->title]); // Should NOT contain Post A.
    }
}
