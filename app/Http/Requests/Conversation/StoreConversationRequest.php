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
        $user = $this->user('web');
        if (! $user) {
            return false;
        }
        $order_id = $this->input('shop_order_id');
        if (! $order_id) {
            return false;
        }
        $order = ShopOrder::query()->find($order_id);
        if (! $order) {
            return false;
        }
        if ((int) $order->user_id !== (int) $user->id) {
            return false;
        }
        $exist_check = Conversation::query()
            ->where('shop_order_id', $order->id)
            ->whereIn('status', ['open', 'waiting_admin'])
            ->exists();

        return ! $exist_check;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shop_order_id' => ['required', 'integer', 'exists: shop_orders,id'],
        ];
    }
}
