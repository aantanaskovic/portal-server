<?php

use App\Enums\TokenAbility;
use App\Models\User;
use App\Models\Post;
use Laravel\Sanctum\Sanctum;

/**
 * Helper function for user authentication using Sanctum with specific privileges
 */
function sanctumActingAs(array $abilities = []): User
{
    $user = User::factory()->create();

    Sanctum::actingAs($user, $abilities);

    return $user;
}

/**
 * INDEX
 */
test('index returns a collection of posts formatted via resource', function () {
    Post::factory()->count(3)->create();

    sanctumActingAs([TokenAbility::POST_READ->value]);

    $response = $this->getJson('/api/posts');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'content', 'user']
            ],
            'links',
            'meta'
        ]);
});

/**
 * STORE
 */
test('store blocks the request with 403 if token lacks create ability', function () {
    sanctumActingAs([TokenAbility::POST_READ->value]);

    $response = $this->postJson('/api/posts', [
        'title' => 'New Server Post',
        'content' => 'Content of the post'
    ]);

    $response->assertStatus(403);
});

test('store creates a post and returns 201 with resource data when token has correct ability', function () {
    sanctumActingAs([TokenAbility::POST_CREATE->value]);

    $postData = [
        'title' => 'Valid Title',
        'content' => 'Valid content text.'
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(201)
        ->assertJsonPath('data.title', 'Valid Title');

    $this->assertDatabaseHas('posts', [
        'title' => 'Valid Title'
    ]);
});

test('store returns 422 validation errors if request data is invalid', function () {
    sanctumActingAs([TokenAbility::POST_CREATE->value]);

    $response = $this->postJson('/api/posts', [
        'title' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'content']);
});

/**
 * SHOW
 */
test('show returns a single post resource', function () {
    $post = Post::factory()->create();
    sanctumActingAs([TokenAbility::POST_READ->value]);

    $response = $this->getJson("/api/posts/{$post->id}");

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $post->id)
        ->assertJsonPath('data.title', $post->title);
});

test('show returns 404 if post does not exist', function () {
    sanctumActingAs([TokenAbility::POST_READ->value]);

    $response = $this->getJson('/api/posts/999999');

    $response->assertStatus(404);
});

/**
 * UPDATE
 */
test('update blocks the request with 403 if token lacks update ability', function () {
    $post = Post::factory()->create();
    sanctumActingAs([TokenAbility::POST_READ->value]);

    $response = $this->patchJson("/api/posts/{$post->id}", [
        'title' => 'Updated Title'
    ]);

    $response->assertStatus(403);
});

test('update modifies the post when token has correct update ability', function () {
    $post = Post::factory()->create(['title' => 'Old Title']);
    sanctumActingAs([TokenAbility::POST_UPDATE->value]);

    $updateData = [
        'title' => 'Brand New Title',
        'content' => 'Updated content text.'
    ];

    $response = $this->patchJson("/api/posts/{$post->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Brand New Title');

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Brand New Title'
    ]);
});

/**
 * DESTROY
 */
test('destroy blocks deletion with 403 if token lacks delete ability', function () {
    $post = Post::factory()->create();
    sanctumActingAs([TokenAbility::POST_READ->value]);

    $response = $this->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});

test('destroy deletes the post and returns 204 no content when token has correct ability', function () {
    $post = Post::factory()->create();
    sanctumActingAs([TokenAbility::POST_DELETE->value]);

    $response = $this->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});
