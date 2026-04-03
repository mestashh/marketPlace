<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Events\Order\OrderCreated;
use App\Exceptions\CartItem\ProductVariantStockException;
use App\Exceptions\Order\AddressNotFoundException;
use App\Exceptions\Order\CartIsEmptyException;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\ShopOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService
{
    public function index(User $user)
    {
        if ($user->isAdmin()) {
            return Order::paginate(20);
        } else {
            return Order::where('user_id', $user->id)->get();
        }
    }

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
            if (! $user->addresses()
                ->where('id', $data['address_id'])
                ->exists()) {
                throw new AddressNotFoundException;
            }
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $data['address_id'],
                'status' => OrderStatusEnum::CREATED->value,
                'total_price' => $totalPrice,
            ]);

            foreach ($grouped as $shopId => $items) {
                $subtotalPrice = 0;
                foreach ($items as $item) {
                    $variant = ProductVariant::where('id', $item->product_variant_id)
                        ->lockForUpdate()
                        ->first();
                    if ($variant->stock < $item->quantity) {
                        throw new ProductVariantStockException;
                    }
                    $variant->decrement('stock', $item->quantity);
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

            event(new OrderCreated($order->id));

            return $order;
        });
    }

    /**
     * @throws AddressNotFoundException
     */
    public function update(User $user, array $data, Order $order): Order
    {
        if (! $user->addresses()
            ->where('id', $data['address_id'])
            ->exists()) {
            throw new AddressNotFoundException;
        }

        $order->update($data);

        return $order;
    }
}
