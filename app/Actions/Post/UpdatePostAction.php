<?php

namespace App\Actions\Post;

use App\Models\Post;

class UpdatePostAction
{
    public function execute(array $data, Post $post): Post
    {
        $post->update($data);

        return $post;
    }
}
