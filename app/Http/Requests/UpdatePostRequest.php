<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'      => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'title'        => ['sometimes', 'required', 'string', 'max:255'],
            'content'      => ['sometimes', 'required', 'string'],
            'published_at' => ['sometimes', 'nullable', 'date'],

            'tags'   => ['sometimes', 'nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }
}
