<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seller::all()->each(function ($seller) {
            Shop::factory()->create([
                'seller_id' => $seller->id,
            ]);
        });
    }
}
