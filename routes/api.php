<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['data' => 'OK']);
});

Route::apiResource('posts', PostController::class);
