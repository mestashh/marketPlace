<?php

namespace App\Http\Requests\Payment;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
        $orderId = $this->integer('order_id');
        if (! $orderId) {
            return false;
        }
        $order = Order::query()->find($orderId);
        if (! $order) {
            return false;
        }
        if ((int) $user->id !== (int) $order->user_id) {
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
            'order_id' => ['required', 'integer', 'exists: orders,id'],
            'payment_method_id' => ['required', 'integer', 'exists: payment_methods,id'],
        ];
    }
}
