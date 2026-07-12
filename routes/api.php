<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class)

    // Handle missing resources with a 404 JSON response
    ->missing(function () {
        return response()->json([
            'errors' => [
                'message' => 'Post not found.',
            ],
        ], 404);
    });
