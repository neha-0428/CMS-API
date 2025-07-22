<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'status' => 'required|in:Draft,Published,Archived',
            'published_date' => 'required|date',
        ];
    }


    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
            'content.max' => 'The content may not be greater than 65535 characters.',

            'category_id.required' => 'At least one category is required.',
            'category_id.array' => 'The category must be an array.',
            'category_id.*.exists' => 'One or more selected categories are invalid',

            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either Draft, Published, or Archived.',

            'published_date.required' => 'The published date is required.',
            'published_date.date' => 'The published date must be a valid date.',
        ];
    }
}
