<?php

namespace App\Http\Controllers\Api;

use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Actions\Post\UpdatePostAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreApiPostFormRequest;
use App\Http\Requests\Api\UpdateApiPostFormRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Post::class);

        $posts = Post::forIndex()
            ->paginate(10)
            ->withQueryString();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApiPostFormRequest $request, CreatePostAction $action)
    {
        $post = $action->execute($request->validated(), $request->user());

        return PostResource::make($post)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        Gate::authorize('view', $post);

        return PostResource::make($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApiPostFormRequest $request, Post $post, UpdatePostAction $action)
    {
        $post = $action->execute($request->validated(), $post);

        return PostResource::make($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, DeletePostAction $action)
    {
        Gate::authorize('delete', $post);

        $action->execute($post);

        return response()->noContent();
    }
}
