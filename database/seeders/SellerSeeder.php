<?php

namespace Database\Seeders;

use App\Models\PayoutMethod;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\SellerPayoutMethod;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seller::factory()
            ->count(10)
            ->has(Shop::factory()
                ->has(Product::factory(5)
                    ->has(ProductVariant::factory(2))
                    ->has(ProductImage::factory()
                        ->count(4)
                        ->sequence(
                            ['position' => 1],
                            ['position' => 2],
                            ['position' => 3],
                            ['position' => 4],
                        ))))
            ->create()
            ->each(function ($seller) {
                $methods = PayoutMethod::all();
                foreach ($methods as $method) {
                    SellerPayoutMethod::factory()
                        ->forMethod($method)
                        ->create([
                            'seller_id' => $seller->id,
                            'payout_method_id' => $method->id,
                        ]);
                }
            });
    }
}
