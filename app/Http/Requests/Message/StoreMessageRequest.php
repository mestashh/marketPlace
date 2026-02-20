<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    private ?int $senderUserId = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $conversation = $this->route('conversation');
        if ((! $conversation) || ($conversation->status === 'closed')) {
            return false;
        }
        if ($user = $this->user('web')) {
            if ((int) $user->id === (int) $conversation->user_id) {
                $this->senderUserId = (int) $user->id;
            }

            return true;
        }

        if ($seller = $this->user('seller')) {
            if ((int) $seller->id === (int) $conversation->seller_id) {
                $this->senderUserId = (int) $seller->id;
            }

            return true;
        }

        if ($admin = $this->user('admin')) {
            if ((is_null($conversation->admin_id)) || ((int) $admin->id !== (int) $conversation->admin_id)) {
                return false;
            }
            $this->senderUserId = (int) $admin->id;

            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => ['text', 'required'],
        ];
    }

    public function senderUserid(): int
    {
        return $this->senderUserId ?? 0;
    }
}
