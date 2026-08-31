<?php

namespace App\Http\Requests;

use App\Models\Post;


class StorePostFormRequest extends BasePostFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('create', Post::class);
    }
}
