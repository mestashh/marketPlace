<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $order = $this->route('order');
        if ($user === null || $order->user_id !== $user->id) {
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
            'shipping_address' => ['required', 'string', 'min:10', 'max:255'],
            'comment' => ['sometimes', 'string', 'max:200'],
        ];
    }
}
