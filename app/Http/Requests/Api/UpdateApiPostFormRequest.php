<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BasePostFormRequest;
use App\Models\Post;


class UpdateApiPostFormRequest extends BasePostFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $this->user() && $this->user()->can('update', $post);
    }
}
