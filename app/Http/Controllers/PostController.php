<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * Get all blog posts, with optional search filtering.
     *
     * Searches by title and category when the 'search' query parameter is provided.
     * Uses Eloquent where() with LIKE for safe search (no raw queries).
     *
     * @param  Request  $request  The incoming HTTP request.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Post::query();

        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%');
            });
        }

        $posts = $query->get();

        return PostResource::collection($posts);
    }

    /**
     * Create a new blog post.
     *
     * Validation is handled by StorePostRequest FormRequest.
     * Returns 201 Created status code on success.
     *
     * @param  StorePostRequest  $request  The validated incoming request.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());

        return PostResource::make($post)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Get a single blog post by ID.
     *
     * @param  int  $id  The post ID.
     */
    public function show(int $id): PostResource|JsonResponse
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'errors' => ['message' => 'Post not found.'],
            ], 404);
        }

        return PostResource::make($post);
    }

    /**
     * Update an existing blog post.
     *
     * Supports partial updates via PATCH. Validation is handled by UpdatePostRequest.
     *
     * @param  UpdatePostRequest  $request  The validated incoming request.
     * @param  int  $id  The post ID.
     */
    public function update(UpdatePostRequest $request, int $id): PostResource|JsonResponse
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'errors' => ['message' => 'Post not found.'],
            ], 404);
        }

        $post->update($request->validated());

        return PostResource::make($post);
    }

    /**
     * Delete a blog post by ID.
     *
     * @param  int  $id  The post ID.
     */
    public function destroy(int $id): JsonResponse
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'errors' => ['message' => 'Post not found.'],
            ], 404);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
