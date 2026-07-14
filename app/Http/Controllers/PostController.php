<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * Get all blog posts, with optional search filtering.
     *
     * Searches by title and category when the 'search' query parameter is provided.
     *
     * @param  Request  $request  The incoming HTTP request.
     */
    #[QueryParameter(name: 'search', description: 'Search term for filtering posts by title or category', required: false)]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Post::query();

        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
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
     * @param  Post  $post The ID of the post to retrieve
     */
    public function show(Post $post): PostResource|JsonResponse
    {
        return PostResource::make($post);
    }

    /**
     * Update an existing blog post.
     *
     * Supports partial updates via PATCH. Validation is handled by UpdatePostRequest.
     *
     * @param  UpdatePostRequest  $request  The validated incoming request.
     * @param  Post  $post  The post model to update.
     */
    #[Endpoint(method: 'PATCH')]
    public function update(UpdatePostRequest $request, Post $post): PostResource|JsonResponse
    {
        $post->update($request->validated());

        return PostResource::make($post);
    }

    /**
     * Delete a blog post by ID.
     *
     * @param  Post  $post  The post model to delete.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
