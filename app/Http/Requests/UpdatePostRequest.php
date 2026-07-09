<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * Get the validation rules for updating a blog post.
     *
     * Rules use 'sometimes' to support partial updates via PATCH method.
     * When a field is present, 'required' ensures it is not empty.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string', 'max:5000'],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
        ];
    }

    /**
     * Get custom human-friendly validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your post.',
            'title.string' => 'The title must be a valid text.',
            'title.max' => 'The title is too long. Please keep it under 255 characters.',
            'content.required' => 'Please write some content for your post.',
            'content.string' => 'The content must be a valid text.',
            'content.max' => 'The content is too long. Please keep it under 5000 characters.',
            'category.required' => 'Please choose a category for your post.',
            'category.string' => 'The category must be a valid text.',
            'category.max' => 'The category name is too long. Please keep it under 100 characters.',
            'tags.array' => 'Tags should be provided as a list.',
            'tags.*.string' => 'Each tag must be a valid text.',
        ];
    }
}
