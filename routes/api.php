<?php

use App\Filters\TitleOrContentFilter;
use App\Http\Controllers\Api\PostController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

Route::name('api.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/user/abilities', function (Request $request) {
        return response()->json([
            'abilities' => $request->user()->currentAccessToken()->abilities
        ]);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/posts', PostController::class);
});
