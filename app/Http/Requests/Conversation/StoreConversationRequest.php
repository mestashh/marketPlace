<?php

namespace App\Http\Requests\Conversation;

use App\Models\Conversation;
use App\Models\ShopOrder;
use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
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
            'shop_order_id' => ['required', 'integer', 'exists:shop_orders,id'],
        ];
    }
}
