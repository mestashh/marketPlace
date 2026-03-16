<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                UserSeeder::class,
                AddressSeeder::class,
                SellerSeeder::class,
                ShopSeeder::class,
                CategorySeeder::class,
                ProductSeeder::class,
                AdminSeeder::class,
                PaymentMethodSeeder::class,
                PayoutMethodSeeder::class,
                ProductVariantSeeder::class,
                ProductImageSeeder::class,
            ]);
    }
}
