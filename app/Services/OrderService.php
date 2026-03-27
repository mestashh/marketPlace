<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Events\Order\OrderCreated;
use App\Exceptions\Order\CartIsEmptyException;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShopOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService
{
    /**
     * @throws Throwable
     */
    public function store(User $user, array $data, Cart $cart)
    {
        $totalPrice = 0;
        $cartItems = $cart->cartItems()->with('productVariant.product')->get();
        if ($cartItems->isEmpty()) {
            throw new CartIsEmptyException;
        }
        foreach ($cartItems as $cartItem) {
            $totalPrice += $cartItem->price * $cartItem->quantity;
        }

        $grouped = $cartItems->groupBy(function ($cartItem) {
            return $cartItem->productVariant->product->shop_id;
        });

        return DB::transaction(function () use ($user, $data, $totalPrice, $grouped, $cart) {
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $data['address_id'],
                'status' => OrderStatusEnum::CREATED->value,
                'total_price' => $totalPrice,
            ]);

            foreach ($grouped as $shopId => $items) {
                $subtotalPrice = 0;
                foreach ($items as $item) {
                    $subtotalPrice += $item->price * $item->quantity;
                }
                $shopOrder = ShopOrder::create([
                    'order_id' => $order->id,
                    'shop_id' => $shopId,
                    'status' => OrderStatusEnum::CREATED->value,
                    'subtotal_price' => $subtotalPrice,
                ]);
                foreach ($items as $item) {
                    OrderItem::create([
                        'shop_order_id' => $shopOrder->id,
                        'product_variant_id' => $item->product_variant_id,
                        'quantity' => $item->quantity,
                        'price_at_purchase' => $item->price,
                    ]);
                }
            }
            $cart->cartItems()->delete();

            event(new OrderCreated($order));
            return $order;
        });
    }
}
