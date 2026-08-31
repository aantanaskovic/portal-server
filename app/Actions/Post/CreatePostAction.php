<?php

namespace App\Actions\Post;

use App\Models\Post;
use App\Models\User;

class CreatePostAction
{
    public function execute(array $data, User $user): Post
    {
        $post = $user->posts()->create($data);

        return $post;
    }
}
