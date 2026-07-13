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
        // 1. Buat sebuah post untuk diambil datanya
        $post = Post::factory()->create();

        // 2. Panggil endpoint untuk mengambil post tersebut
        $response = $this->getJson('/api/posts/' . $post->id);

        // 3. Pastikan responsnya benar
        $response
            ->assertStatus(200)
            ->assertJsonStructure([ // Periksa struktur JSON sesuai PRD.md
                'id',
                'title',
                'content',
                'category',
                'tags',
                'createdAt',
                'updatedAt',
            ])
            ->assertJson([ // Pastikan data yang dikembalikan sesuai dengan yang dibuat
                'id' => $post->id,
                'title' => $post->title,
            ]);
    }

    #[Test]
    #[TestDox('It should return a 404 Not Found error if the post does not exist')]
    public function it_returns_404_not_found_if_post_does_not_exist(): void
    {
        $nonExistentId = 999;

        // Panggil endpoint dengan ID yang tidak ada di database
        $response = $this->getJson('/api/posts/' . $nonExistentId);

        // Pastikan responsnya adalah 404 dengan format error yang benar
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'errors' => [
                    'message' => 'Post not found.'
                ]
            ]);
    }

    #[Test]
    #[TestDox('It should also return a 404 Not Found for non-numeric IDs')]
    public function it_returns_404_not_found_for_non_numeric_ids(): void
    {
        $invalidId = 'abc'; // ID dengan format yang salah (bukan angka)

        // Panggil endpoint dengan ID non-numerik
        $response = $this->getJson('/api/posts/' . $invalidId);

        // Pastikan method missing() di routing bekerja dan mengembalikan 404
        // dengan format error yang sama seperti ID yang tidak ditemukan.
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'errors' => [
                    'message' => 'Post not found.'
                ]
            ]);
    }
}
