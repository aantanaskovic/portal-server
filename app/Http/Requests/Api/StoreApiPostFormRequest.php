<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BasePostFormRequest;
use App\Models\Post;


class StoreApiPostFormRequest extends BasePostFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('create', Post::class);
    }
}
