<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestAdminForConversationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $conversation = $this->route('conversation');
        if ($user === null || $user->id !== $conversation->user_id || $conversation->status !== 'closed') {
            return false;
        }

        return true;
    }

    public function prepareForValidation(): array
    {
        $priority = $this->input('priority');

        return [
            'priority' => strtolower($priority),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'min:20', 'max:200'],
            'priority' => ['required', Rule::in(['low', 'normal', 'high'])],
        ];
    }
}
