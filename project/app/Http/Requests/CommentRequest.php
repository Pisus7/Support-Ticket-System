<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class CommentRequest
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
            'content' => 'required|string|min:5|max:800',
        ];
    }

    public function messages(): array
    {
        return [
            'ticket.required' => 'You must provide content for your comment.',
            'ticket.min' => 'Sure your ticket subject is good, but it must be at least :min characters long.',
            'ticket.max' => 'Make sure your ticket doesn\'t have more than :max characters.',
        ];
    }
}
