<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'comment_text' => 'required|string|min:5|max:800',
        ];
    }

    public function messages(): array
    {
        return [
            'comment_text.required' => 'You must provide content for your comment.',
            'comment_text.min' => 'Sure your comment content is good, but it must be at least :min characters long.',
            'comment_text.max' => 'Make sure your comment doesn\'t have more than :max characters.',
        ];
    }
}
