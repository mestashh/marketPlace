<?php

namespace App\Http\Requests\Test;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $seller = $this->user('seller');
        $admin = $this->user('admin');
        $conversation = $this->route('conversation');
        if ($user === null || $user->id !== $conversation->user_id || $conversation->status === 'closed' || $seller === null || $admin === null || $conversation->admin_id !== $admin->id || $conversation->seller_id !== $seller->id) {
            return false;
        }

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
            'body' => ['required', 'text', 'min:1', 'max:2000'],
            'attachments' => ['sometimes', 'array', 'max:5'], // как прописать логику со строками в этом случае не знаю
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $body = $this->input('body');
            $attachments = $this->input('attachments');
            if ($attachments !== null && strlen($body) < 2) {
                $validator->errors()->add($body, 'Если прикрепляешь файлы, добавь пояснение текстом');
            }
        });
    }
}
