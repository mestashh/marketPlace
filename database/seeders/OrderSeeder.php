<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Shop;
use App\Models\ShopOrder;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()
            ->count(10)
            ->create()
            ->each(function ($order) {
                $shops = Shop::inRandomOrder()->take(2)->get();
                foreach ($shops as $shop) {
                    $shopOrder = ShopOrder::factory()->create([
                        'order_id' => $order->id,
                        'shop_id' => $shop->id,
                    ]);
                    $variants = ProductVariant::inRandomOrder()->take(5)->get();
                    foreach ($variants as $variant) {
                        OrderItem::factory()->create([
                            'shop_order_id' => $shopOrder->id,
                            'product_variant_id' => $variant->id,
                        ]);
                    }
                }
            });
    }
}
