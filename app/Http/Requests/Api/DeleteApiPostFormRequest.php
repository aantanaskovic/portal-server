<?php

namespace App\Http\Requests\Api;

use App\Enums\TokenAbility;
use Illuminate\Foundation\Http\FormRequest;


class DeleteApiPostFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->tokenCan(TokenAbility::POST_DELETE->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
