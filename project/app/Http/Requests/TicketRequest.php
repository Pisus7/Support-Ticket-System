<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'ticket_subject' => 'required|string|min:5|max:255',
            'ticket_message' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ticket.required' => 'You must provide a complete ticket.',
            'ticket.min' => 'Sure your ticket subject is good, but it must be at least :min characters long.',
        ];
    }
}
